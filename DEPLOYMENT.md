# Deployment and domain change

The site is built so that its links do not depend on the folder name or the domain:

- Menu links, mega-menu sub-items, slider buttons: resolved at render time by
  `App\Support\AppLink` from the current request URL.
- Images and uploads: `asset()` / `PublicMedia::url()`, also request based.
- Canonical tags, sitemap.xml and robots.txt: built with `url()` / `route()`.
- Stored data contains no hard-coded `localhost` or domain addresses (checked in the SQL file).

## First deployment on a host

1. Upload the project. Point the web root to the `public/` folder.
2. Copy `.env.example` to `.env` and set `APP_URL=https://your-domain`, `APP_ENV=production`,
   `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, and the database values.
3. If the site is behind a CDN or load balancer, set `TRUSTED_PROXIES` (IP list or `*`).
4. `composer install --no-dev --optimize-autoloader`, `php artisan key:generate`.
5. Import `database/ideban_mysql.sql` into an empty database (do not run migrations afterwards).
6. `php artisan storage:link` (uploaded media), `php artisan config:cache`, `php artisan route:cache`.

## Changing the domain later

- Nothing in the database needs to change: menu, slider and content links are relative.
- Update `APP_URL` in `.env`, then run `php artisan config:clear && php artisan config:cache`.
- Clear the browser cache once after a deployment (CSS and JS have version numbers).
- Check: home page, one service, the mega menu, `/sitemap.xml`, `/robots.txt`, and the admin login.

## Folder changes

If the project folder is renamed (for example `localhost/ideban-laravel/public` becomes
`localhost/site`), no code changes are needed. Links follow the new base automatically.
