#!/usr/bin/env bash
set -euo pipefail

PROJECT_DIR="/home/drmomin/htdocs/dralimoradi.moghadam.pro"
BRANCH="agent/catalog-source-content"
PROCESS_NAME="dr-alimoradi-demo"
HEALTH_URL="http://127.0.0.1:3006/"

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

pm2 restart "$PROCESS_NAME" --update-env
pm2 save

for attempt in 1 2 3 4 5; do
  if curl --fail --silent --show-error --head "$HEALTH_URL" >/dev/null; then
    echo "Deployment is healthy at $HEALTH_URL"
    exit 0
  fi
  sleep 2
done

echo "The process restarted, but the health check did not pass."
pm2 status "$PROCESS_NAME"
pm2 logs "$PROCESS_NAME" --lines 80 --nostream
exit 1
