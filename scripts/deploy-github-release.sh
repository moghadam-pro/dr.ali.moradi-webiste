#!/usr/bin/env bash
set -Eeuo pipefail

REF="${1:-main}"
REPOSITORY="${MORADI_GITHUB_REPOSITORY:-moghadam-pro/dr.ali.moradi-webiste}"
DEPLOY_USER="${MORADI_DEPLOY_USER:-drmomin}"
APP_DIR="${MORADI_APP_DIR:-/home/drmomin/htdocs/dralimoradi.moghadam.pro}"
PM2_NAME="${MORADI_PM2_NAME:-dr-alimoradi-demo}"
HEALTH_URL="${MORADI_HEALTH_URL:-http://127.0.0.1:3006/}"
SOURCE_PARENT="$(dirname -- "$APP_DIR")"

[[ "$REF" =~ ^[A-Za-z0-9._/-]+$ ]] || { echo "Unsafe Git reference." >&2; exit 64; }
[[ "$REPOSITORY" =~ ^[A-Za-z0-9._-]+/[A-Za-z0-9._-]+$ ]] || { echo "Unsafe GitHub repository name." >&2; exit 64; }
[[ -d "$APP_DIR" && -f "$APP_DIR/package.json" ]] || { echo "Live application directory is not valid: $APP_DIR" >&2; exit 66; }
command -v curl >/dev/null && command -v tar >/dev/null && command -v sudo >/dev/null || { echo "curl, tar, and sudo are required." >&2; exit 69; }

SOURCE_DIR="$(mktemp -d "$SOURCE_PARENT/.dr-moradi-github.XXXXXX")"
case "$SOURCE_DIR/" in "$SOURCE_PARENT/.dr-moradi-github."*) ;; *) echo "Unsafe temporary directory." >&2; exit 65 ;; esac
cleanup() { rm -rf -- "$SOURCE_DIR"; }
trap cleanup EXIT

echo "Downloading $REPOSITORY at $REF"
curl --fail --location --silent --show-error \
  "https://github.com/$REPOSITORY/archive/$REF.tar.gz" \
  | tar -xz --strip-components=1 -C "$SOURCE_DIR"

[[ -f "$SOURCE_DIR/package.json" && -f "$SOURCE_DIR/scripts/deploy-uploaded-release.sh" ]] || {
  echo "Downloaded release is missing required project files." >&2
  exit 66
}

chown -R "$DEPLOY_USER:$DEPLOY_USER" "$SOURCE_DIR"
echo "Building and deploying as $DEPLOY_USER"
sudo -iu "$DEPLOY_USER" bash -lc \
  "node --version && bash '$SOURCE_DIR/scripts/deploy-uploaded-release.sh' '$SOURCE_DIR' '$APP_DIR' 'pm2:$PM2_NAME' '$HEALTH_URL'"
