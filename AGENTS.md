# AGENTS.md — Prince Full (PHP + MySQL)

Notes for working on this repo in the Base44 dev environment.

## Stack
- PHP 8.2 + Apache (mod_rewrite, .htaccess) + PDO MySQL
- MariaDB 11 (local compose service)
- Arabic RTL, vanilla HTML/CSS/JS, no build step, no Node.js
- PHP re-executes per request — edits to PHP/HTML/CSS/JS are live immediately (no reload needed)

## Running
- `docker compose -f docker-compose.base44.yml up -d` (builds `Dockerfile.base44`)
- Web on host port 3000 → Apache :80 inside. DB is the `db` service (MariaDB).
- Source is bind-mounted at `/var/www/html`; the `Dockerfile.base44` image only adds the `pdo_mysql` extension, `mod_rewrite`, `curl`, and `AllowOverride All`.

## Database
- Schema auto-seeds on first DB start via `database/schema.sql` (mounted into MariaDB initdb.d). It creates the `prince_cards` DB, the `admins` table, and one seed admin (`ibrabra651@gmail.com`).
- To re-seed, drop the `dbdata` volume: `docker compose -f docker-compose.base44.yml down -v` then `up -d`.
- Local DB credentials are set in compose `environment:` (prince / princepass), NOT secrets — they are local infra only.

## config/database.php
- Made environment-driven: reads `DB_HOST/DB_NAME/DB_USER/DB_PASS/DB_PORT` env vars, falling back to the original external InfinityFree hosting credentials when unset (production). Uses `define()` (not `const`, which can't hold function calls in PHP).
- No external credentials are needed for local dev — the DB runs in compose.

## Permissions quirk
- The sandbox repo root is mode 700; Apache's `www-data` worker can't traverse a bind-mounted 700 dir (403 "unable to read htaccess file"). `chmod -R a+rX .` on the host fixes it. Re-run if a fresh checkout resets perms.

## Login
- Admin email: `ibrabra651@gmail.com` (password is the owner's, set in `database/schema.sql` via bcrypt hash). The login page validates against the `admins` table with `password_verify()`.
- `dashboard.php` is currently static (KPIs hard-coded to 0); no other DB tables are queried, so it renders without additional seeding.

## Healthchecks
- `db`: MariaDB `healthcheck.sh --connect --innodb_initialized`
- `web`: `curl -sf http://localhost/` (index.php 302-redirects to login.php, both are non-error responses)
