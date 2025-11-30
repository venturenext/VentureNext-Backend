# PerkPal Backend

Laravel 11 powers the PerkPal REST API, job queue workers, and the admin surface. Sanctum secures the admin namespace while public endpoints expose perks, categories, journals, locations, CMS page content, lead forms, and tracking hooks for the Svelte front-end.

## Key features

- Public `v1` routes for perks, categories, locations, static pages, journal posts, leads, and analytics tracking.
- Admin `v1/admin` namespace managed via Sanctum with dashboards, perks/categories/journal management, CMS sections, analytics, and lead/inbox workflows.
- Background work handled via Laravel queues (`php artisan queue:work` or `queue:listen`) plus Pail/Concurrently powered dev tooling.
- Asset pipeline is driven by Vite, Tailwind, and Laravel Vite Plugin so the front-end can consume shared components.

## Prerequisites

- PHP **≥ 8.2**
- Composer
- Node.js **20.x** (for Vite/Tailwind asset compilation)
- A supported database (MySQL, PostgreSQL, SQLite, etc.)
- Redis or another queue driver if you enable async workers (optional for default dev mode)
- SMTP credentials or a Resend API key (the `/app/Jobs/SendLeadNotificationEmail.php` job prefers Resend but can fall back to SMTP)

## Getting started

1. `composer install`
2. `cp .env.example .env` and adjust `APP_URL`, database credentials, mailer settings, `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN`, and any `RESEND_*` keys.
3. `php artisan key:generate`
4. `php artisan migrate --graceful`
5. `php artisan storage:link`
6. `npm install` (needed for the Vite build scripts referenced in `resources/` and `package.json`)

## Running locally

Use `php artisan serve --host 127.0.0.1 --port 8000` plus any of the following helpers:

```
npm run dev            # Vite dev server for the shared asset pipeline (Tailwind, Axios helpers, etc.)
composer dev          # runs serve, queue listener, pail, and npm dev server via concurrently
php artisan queue:work # keep queues flowing outside the dev helper
```

Long-running workers or scheduled jobs can be started with `php artisan queue:work` and `php artisan schedule:work` if needed.

## Testing and linting

- `php artisan test` or `vendor/bin/phpunit`
- `npm run dev` already wires up Tailwind/Vite assets that the tests assume are present; run `npm run build` before deployment to refresh the generated assets.

## Documentation & API explorer

- Swagger/OpenAPI docs live under `public/docs/index.html`; regenerate them whenever the API changes so the UI remains accurate.
- Open that HTML file directly or serve the `public/` folder to review the interactive Swagger UI during development or demos.

## Email & SMTP

- Set `MAIL_MAILER` to `resend`, `smtp`, or another driver (defaults to `log` locally). Resend is preferred, but SMTP serves as the fallback when Resend is not configured.
- Populate `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_TIMEOUT`, `MAIL_SCHEME`, `MAIL_URL`, and `MAIL_EHLO_DOMAIN` when using SMTP. `config/mail.php` already wires failover mailers (`resend`, `smtp`, `log`) so notifications stay resilient.

## API snapshot

**Public endpoints (no auth):** perks (`/v1/perks`), categories, locations, journal, pages, settings, page-content, lead submissions, and tracking (`/track/...`).  
**Protected admin endpoints:** `/v1/admin/dashboard`, resourceful controllers for perks/categories/subcategories/locations/journal/pages, lead and inbox management, CMS content uploader, analytics, and settings updates all behind Sanctum tokens.

## Deployment notes

- Cache configs: `php artisan config:cache`, `php artisan route:cache`
- Queue supervisor should run `php artisan queue:work --sleep=3 --tries=3`
- When deploying, rebuild assets with `npm run build` and refresh storage symlinks via `php artisan storage:link`.
- API documentation lives at `public/docs/index.html`; update the Swagger bundle whenever the API surface changes so the explorer stays accurate.
- Ensure `.env` contains the right mail settings (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`, `MAIL_TIMEOUT`, etc.) before deploying.
