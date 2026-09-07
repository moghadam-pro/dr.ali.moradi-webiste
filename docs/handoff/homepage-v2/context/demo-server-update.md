# Demo Server Update

The public demo runs behind CloudPanel/Nginx at
`https://dralimoradi.moghadam.pro` and the Node process listens locally on port
`3006`.

## One-command update

After connecting to the server, run:

```bash
cd /home/drmomin/htdocs/dralimoradi.moghadam.pro
bash scripts/update-demo-server.sh
```

The script:

1. activates and verifies the CloudPanel Node.js 22 runtime;
2. fast-forwards the approved demo branch;
3. installs the locked dependencies;
4. creates a production build;
5. restarts the existing PM2 process as the `drmomin` site user;
6. checks the local service on port `3006`.

The PM2 process name is `dr-alimoradi-demo` and the tracked branch is
`agent/catalog-source-content`.

## Verification

After a successful update:

```bash
curl -I http://127.0.0.1:3006/
curl -I https://dralimoradi.moghadam.pro/
pm2 status dr-alimoradi-demo
```

Both HTTP checks should return a successful response and PM2 should report the
process as `online`.

## If the shell still selects an older Node version

Activate the same Node.js 22 runtime configured for the CloudPanel application,
then run the update script again. Confirm the selected runtime first:

```bash
node --version
npm --version
```

Do not restart PM2 until `npm run build` completes successfully.
