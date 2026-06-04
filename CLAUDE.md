# CLAUDE.md

Laravel (PHP 7.4-fpm) deployed as two images (`codelab`, `codelab-nginx`) to the `codelab` stack. The canonical platform contract — networks, middlewares, deploy action, stack template — lives in [codelab-infra](https://git.codelab.tec.br/codelab/infra) (`CONTEXT.md`, `docs/adr/`, `templates/stack/`). Read it when in doubt.

## Rules (not inferable from the code)

- **The deploy action must be a full Gitea URL** (`uses: https://git.codelab.tec.br/...`); the short form doesn't resolve. The shared `deploy-stack` action renders the host `.env`, rsyncs `compose.yml`, and runs `docker compose up -d`.
- **Never add a stack-local network.** `traefik-public` and `mariadb-internal` are external and platform-owned; a local network can't reach Traefik or MariaDB.
- **Secrets live only in the `codelab` Vault collection** (`APP_KEY`, `DB_PASSWORD`, mail credentials, GitLab/Zoho/Trello tokens): declare the key blank in `.env.example`, store the value in Vault. Every non-secret config — including `DB_USERNAME` — stays as a plain `.env.example` default.
- **`.env.example` is the full env schema**: every variable the app reads, mirroring production with secrets blanked. `APP_ENV` / `APP_DEBUG` / `APP_URL` / `DB_HOST` are re-asserted as `env-overrides` in `deploy.yml` as a guardrail.
- **`clear_env = no` in the Dockerfile is load-bearing**: the host `.env` is delivered via `env_file:` and written mode `600` (unreadable by `www-data`), so php-fpm must propagate the container env to its workers.
- **`/opt/data/codelab/` is owned by the restic backup** — the deploy never writes there.
- This repo carries the Gitea topic `codelab-stack` for the derived stack inventory.
