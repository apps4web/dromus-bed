# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

Website for **Dromus Bed & Boetiek**, a B&B in Zierikzee (NL). CakePHP 5.3 app (PHP >= 8.2) with a MySQL database, running locally under XAMPP. All user-facing text (site content, flash messages, emails) is in **Dutch**; locale `nl_NL`, timezone `Europe/Amsterdam`.

## Commands

```bash
composer test                      # PHPUnit (all tests)
vendor/bin/phpunit tests/TestCase/Controller/ReservationsControllerTest.php   # single test file
vendor/bin/phpunit --filter testMethodName                                    # single test method
composer cs-check                  # PHP CodeSniffer (CakePHP standard, see phpcs.xml)
composer cs-fix                    # auto-fix code style
composer check                     # test + cs-check
bin/cake server -p 8765            # built-in dev server (or serve via XAMPP)
bin/cake cache clear_all
```

On Windows use `bin\cake.bat` (or `php bin/cake.php`). `phpstan.neon` (level 8) and `psalm.xml` exist but the binaries are not installed in `vendor/bin`.

Tests use a separate `test` datasource (`test_dromus_bed` on localhost, see `config/app_local.php`) and load `tests/schema.sql`.

## Database schema

There are **no CakePHP migrations** in this repo. The schema lives as raw SQL in [database/](database/): `dromus_schema.sql` (tables) plus seed files. Schema changes are made by editing/adding SQL there and applying it manually. (The deploy script does call `bin/cake migrations migrate`, which is currently a no-op.)

Tables: `site_texts`, `site_photos`, `site_reviews` (homepage content), `reservations`, `users`.

## Architecture

Two halves, both served by the same app:

**Public site** — a single homepage ([templates/Home/index.php](templates/Home/index.php), rendered without a layout by `HomeController::index`). All content is database-driven:
- `site_texts` rows are keyed as `section_key.field_key` (locale `nl`) and passed to the template as a flat `$texts` array.
- `site_photos` are grouped per `section_key`; `site_reviews` ordered by `sort_order`.
- The reservation form POSTs to `/reservations` → `HomeController::reserve`, which creates a `reservations` row with `source = 'website'`, `status = 'new'` and sends an admin notification email. Confirmed reservation date ranges are passed to the page for the date picker (blocking booked dates).

**Admin CMS** — routes under `/admin` (see [config/routes.php](config/routes.php)):
- `AdminController` manages users, site texts, photos, and reviews.
- `ReservationsController` manages reservations (list, edit, agenda, status updates).
- Login at `/login` via cakephp/authentication (Form authenticator on `email` / `password_hash` fields of `users`).

**Authorization** is role-based and checked inline per action: each admin action reads `$identity->get('role')` and compares against `AdminController::CMS_ROLES` (or requires `admin` exactly for user management). The Authorization plugin is loaded with `requireAuthorizationCheck => false`; the policies in [src/Policy/](src/Policy/) are largely unused — public actions call `skipAuthorization()`.

## Email behavior (deliberate, don't "fix")

All reservation mail goes through [src/Mailer/ReservationMailer.php](src/Mailer/ReservationMailer.php), configured via the `ReservationEmail` Configure keys (env vars `RESERVATION_*`, defaults in `config/app.php`; SMTP via TransIP).

- New website reservation → admin notification **is** sent.
- Guest confirmation email is **intentionally disabled** (commented out in `ReservationsController`).
- Status-change email to the guest is **only** sent when the admin explicitly checks `send_status_email` in the edit form — never automatically (commit "no auto mail at status change").

## Deployment

Git-based deploy to Combell hosting — see [DEPLOY.md](DEPLOY.md). From the dev machine: `ssh USER@SERVER '~/dromus-bed-git/bin/deploy.sh'`, which does `git reset --hard origin/main` → `composer install --no-dev` → migrate → cache clear. Consequences:

- **Never deploy via FTP**; uncommitted server changes are wiped by the hard reset.
- `config/app_local.php` and `config/.env` are gitignored and live only on the server (and locally). Committed defaults/examples go in `config/app.php` and `config/.env.example`.
