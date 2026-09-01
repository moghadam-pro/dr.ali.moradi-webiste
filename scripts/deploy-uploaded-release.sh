#!/usr/bin/env bash
set -Eeuo pipefail

usage() {
  cat <<'EOF'
Usage:
  sudo bash deploy-uploaded-release.sh UPLOAD_DIR APP_DIR RESTART_TARGET [HEALTH_URL]

Examples:
  sudo bash deploy-uploaded-release.sh /srv/uploads/dr-moradi /var/www/dr-moradi pm2:dr-moradi http://127.0.0.1:3000/
  sudo bash deploy-uploaded-release.sh /srv/uploads/dr-moradi /var/www/dr-moradi systemd:dr-moradi.service http://127.0.0.1:3000/

RESTART_TARGET must be pm2:APP_NAME, systemd:UNIT_NAME, or none.
EOF
}

if [[ $# -lt 3 || $# -gt 4 ]]; then
  usage
  exit 64
fi

UPLOAD_DIR="$(realpath -e -- "$1")"
APP_DIR="$(realpath -e -- "$2")"
RESTART_TARGET="$3"
HEALTH_URL="${4:-}"

for required_command in realpath rsync npm curl flock; do
  command -v "$required_command" >/dev/null || {
    echo "Missing required command: $required_command" >&2
    exit 69
  }
done

NODE_MAJOR="$(node -p 'Number(process.versions.node.split(".")[0])')"
if (( NODE_MAJOR < 22 )); then
  echo "Node.js 22 or newer is required; current version: $(node --version)" >&2
  echo "Run this deployment from the CloudPanel user's interactive shell environment." >&2
  exit 69
fi

if [[ "$UPLOAD_DIR" == "/" || "$APP_DIR" == "/" || "$UPLOAD_DIR" == "$APP_DIR" ]]; then
  echo "Refusing unsafe or identical deployment paths." >&2
  exit 65
fi

case "$UPLOAD_DIR/" in "$APP_DIR/"*)
  echo "UPLOAD_DIR cannot be inside APP_DIR." >&2
  exit 65
esac
case "$APP_DIR/" in "$UPLOAD_DIR/"*)
  echo "APP_DIR cannot be inside UPLOAD_DIR." >&2
  exit 65
esac

if [[ ! -f "$UPLOAD_DIR/package.json" || ! -f "$UPLOAD_DIR/package-lock.json" ]]; then
  echo "The uploaded directory is not the expected project root." >&2
  exit 66
fi

case "$RESTART_TARGET" in
  pm2:*|systemd:*|none) ;;
  *)
    echo "RESTART_TARGET must be pm2:NAME, systemd:UNIT, or none." >&2
    exit 64
    ;;
esac

if [[ "$RESTART_TARGET" == pm2:* ]]; then
  command -v pm2 >/dev/null || {
    echo "Missing required command: pm2" >&2
    exit 69
  }
elif [[ "$RESTART_TARGET" == systemd:* ]]; then
  command -v systemctl >/dev/null || {
    echo "Missing required command: systemctl" >&2
    exit 69
  }
fi

APP_PARENT="$(dirname -- "$APP_DIR")"
APP_NAME="$(basename -- "$APP_DIR")"
TIMESTAMP="$(date -u +%Y%m%dT%H%M%SZ)"
BACKUP_DIR="$APP_PARENT/.${APP_NAME}.backup-$TIMESTAMP"
STAGE_DIR="$(mktemp -d "$APP_PARENT/.${APP_NAME}.release.XXXXXX")"
LOCK_FILE="$APP_PARENT/.${APP_NAME}.deploy.lock"
SWAPPED=0

exec 9>"$LOCK_FILE"
flock -n 9 || {
  echo "Another deployment is already running." >&2
  exit 75
}

restart_service() {
  case "$RESTART_TARGET" in
    pm2:*) pm2 restart "${RESTART_TARGET#pm2:}" --update-env ;;
    systemd:*) systemctl restart "${RESTART_TARGET#systemd:}" ;;
    none) ;;
  esac
}

rollback_on_error() {
  local exit_code=$?
  trap - ERR
  if [[ "$SWAPPED" == "1" && -d "$BACKUP_DIR" ]]; then
    echo "Deployment failed; restoring $BACKUP_DIR" >&2
    rsync -a --delete "$BACKUP_DIR/" "$APP_DIR/" || true
    restart_service || true
  fi
  rm -rf -- "$STAGE_DIR"
  exit "$exit_code"
}

trap rollback_on_error ERR
trap 'rm -rf -- "$STAGE_DIR"' EXIT

echo "Preparing release from $UPLOAD_DIR"
rsync -a --delete \
  --exclude='.git/' \
  --exclude='.env*' \
  --exclude='node_modules/' \
  --exclude='dist/' \
  --exclude='.next/' \
  --exclude='.vinext/' \
  --exclude='.wrangler/' \
  --exclude='*.log' \
  "$UPLOAD_DIR/" "$STAGE_DIR/"

shopt -s nullglob
for env_file in "$APP_DIR"/.env*; do
  cp -a -- "$env_file" "$STAGE_DIR/"
done
shopt -u nullglob

echo "Installing locked dependencies"
cd "$STAGE_DIR"
npm ci --include=dev

echo "Building production release"
npm run build
test -f "$STAGE_DIR/dist/server/index.js" || {
  echo "Build output dist/server/index.js was not created." >&2
  exit 70
}

echo "Backing up current application to $BACKUP_DIR"
cp -a --reflink=auto "$APP_DIR" "$BACKUP_DIR"

echo "Replacing the live application"
rsync -a --delete \
  --exclude='.git/' \
  --exclude='.env*' \
  "$STAGE_DIR/" "$APP_DIR/"
SWAPPED=1

echo "Restarting application service"
restart_service

if [[ -n "$HEALTH_URL" ]]; then
  echo "Checking $HEALTH_URL"
  HEALTHY=0
  for attempt in 1 2 3 4 5 6; do
    if curl --fail --silent --show-error --max-time 10 "$HEALTH_URL" >/dev/null; then
      HEALTHY=1
      break
    fi
    sleep 3
  done
  if [[ "$HEALTHY" != "1" ]]; then
    echo "Health check failed after restart." >&2
    false
  fi
fi

SWAPPED=0
trap - ERR
echo "Deployment completed successfully. Backup: $BACKUP_DIR"
