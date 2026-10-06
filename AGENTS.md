# Base44 development notes

## Runtime and edit loop
- Run `docker compose -f docker-compose.base44.yml up -d --build`; the web entry point is port 3000.
- The PHP runtime image only supplies extensions and Apache configuration. The checkout is bind-mounted at `/var/www/html`, and Apache's document root is `public/`.
- PHP executes source on every request (no frontend build step). Refresh the preview after service/configuration changes; do not rebuild for ordinary PHP edits.
- If Apache reports a bind-mount traversal/htaccess 403, check repository directory permissions for `www-data` before changing Apache configuration.

## Routing
- `public/index.php` loads the autoloader, database configuration and session, then dispatches `config/routes.php`.
- The current login URL is `/login`, not `/login.php`. A compatibility GET route redirects the old URL to `/login` so retained preview URLs still work.
- Protected routes must redirect unauthenticated visitors to `/login`. Do not fabricate authenticated sessions when testing.

## Database
- Compose uses a local `prince_cards` database. Hosting uses `if0_43097781_prince`.
- Preserve `database/schema.sql` as the hosting schema. `.base44/init-db.sh` is sourced by MariaDB on first initialization and skips its `USE` directive while importing into `MARIADB_DATABASE`.
- Existing database volumes are retained, and initialization scripts do not rerun for populated volumes. Never delete the application-data volume to repair the preview.
- Local infrastructure credentials are in Compose; external credentials are not needed for this setup.
- SQL references to the reserved table name `lines` must use backticks.

## Rendering quirk
- `Controller::view` must not name its parameter `$data`: `extract(..., EXTR_SKIP)` would retain that parameter instead of extracting the report's `data` rows, causing reports to iterate over the entire view context.

## Verification
- `curl -fsSL http://localhost:3000/login.php` must return the Arabic login page after redirecting to `/login`.
- `docker compose -f docker-compose.base44.yml ps` must show healthy database and web services. Web health probes the existing `/login` endpoint.
- PHP lint: `docker compose -f docker-compose.base44.yml exec -T web sh -c 'find app config public vendor -name "*.php" -exec php -l {} \;'`.
- CLI model/render tests must define `APP_ROOT` as the checkout directory before requiring `vendor/Core/autoload.php`, then load `config/database.php`.
- Report regression checks must include nonempty rows, especially the cash report. Search relies on the runtime's `mbstring` extension.
- Successful administrator login requires the owner's actual password; keep the seed hash unchanged and do not reset it just for a test.
