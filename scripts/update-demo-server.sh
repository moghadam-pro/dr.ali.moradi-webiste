#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/home/drmomin/htdocs/dralimoradi.moghadam.pro"
BRANCH="agent/catalog-source-content"
PROCESS_NAME="dr-alimoradi-demo"
HEALTH_URL="http://127.0.0.1:3006/"
SITE_USER="drmomin"

export NVM_DIR="/home/drmomin/.nvm"
if [ -s "$NVM_DIR/nvm.sh" ]; then
  # CloudPanel manages the selected Node.js version through the site user's NVM.
  # shellcheck disable=SC1091
  source "$NVM_DIR/nvm.sh"
  nvm use 22 >/dev/null
fi

cd "$PROJECT_DIR"

NODE_MAJOR="$(node -p 'Number(process.versions.node.split(".")[0])')"
if [ "$NODE_MAJOR" -lt 22 ]; then
  echo "Node.js 22 or newer is required. Current: $(node --version)"
  exit 1
fi

git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"

npm ci
npm run build

if [ "$(id -un)" = "$SITE_USER" ]; then
  export HOST="0.0.0.0"
  export PORT="3006"
  pm2 restart "$PROCESS_NAME" --update-env
  pm2 save
else
  sudo -u "$SITE_USER" -H bash -lc "
    export NVM_DIR='/home/drmomin/.nvm'
    source \"\$NVM_DIR/nvm.sh\"
    nvm use 22 >/dev/null
    export HOST='0.0.0.0'
    export PORT='3006'
    cd '$PROJECT_DIR'
    pm2 restart '$PROCESS_NAME' --update-env
    pm2 save
  "
fi

for attempt in 1 2 3 4 5; do
  if curl --fail --silent --show-error --head "$HEALTH_URL" >/dev/null; then
    echo "Deployment is healthy at $HEALTH_URL"
    exit 0
  fi
  sleep 2
done

echo "The process restarted, but the health check did not pass."
if [ "$(id -un)" = "$SITE_USER" ]; then
  pm2 status "$PROCESS_NAME"
  pm2 logs "$PROCESS_NAME" --lines 80 --nostream
else
  sudo -u "$SITE_USER" -H bash -lc "
    export NVM_DIR='/home/drmomin/.nvm'
    source \"\$NVM_DIR/nvm.sh\"
    nvm use 22 >/dev/null
    pm2 status '$PROCESS_NAME'
    pm2 logs '$PROCESS_NAME' --lines 80 --nostream
  "
fi
exit 1
