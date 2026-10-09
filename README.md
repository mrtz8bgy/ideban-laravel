# Ideban Almas

Bilingual (Persian RTL / English LTR) IT services storefront and lead-management platform built on the existing Laravel 8 application.

## Phase-one features

- Responsive Persian and English public pages: homepage, service catalog and details, pricing, published portfolio, and consultation/quotation form.
- Server-side validated and rate-limited lead capture.
- Staff login and protected admin dashboard with role checks (`admin`, `content`, `sales`).
- Admin CRUD for service categories, services, plans, price records and sources, and portfolio entries; sales staff can track lead stages, assignment, follow-up date, expected value, and notes.
- Official tariffs are excluded from public results unless their source has been marked verified. Amount display can be turned off independently.
- No sample client projects or official tariff amounts are seeded. Starter service names and quote-only plan labels are catalog placeholders, not claims about delivered projects or fixed prices.

## Requirements

- PHP 7.3+ (PHP 7.4 is supported by the current lock file), Composer, and MySQL 5.7+ or MariaDB with JSON column support.
- Node.js is not needed for the included frontend stylesheet.

## Install

1. Clone/download the project and install dependencies:

   ```sh
   composer install
   ```

2. Create an empty MySQL database and copy `.env.example` to `.env`. Configure `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`. Then generate the application key:

   ```sh
   php artisan key:generate
   ```

3. Choose exactly one database setup option:

   - **Migrations (recommended):** `php artisan migrate --seed`
   - **SQL import:** import `database/ideban_mysql.sql` into an empty MySQL database. The dump includes the migration ledger, so do not run the initial migrations a second time.

4. Create the first administrator interactively. Existing accounts require explicit confirmation before promotion; the command does not print or change an existing password:

   ```sh
   php artisan ideban:make-admin
   ```

5. Start locally:

   ```sh
   php artisan serve
   ```

   Open `/` for the public site and `/admin/login` for staff access.

## Roles

- `admin`: full admin panel access.
- `content`: service catalog, pricing and portfolio management.
- `sales`: dashboard and lead tracking.
- `customer`: reserved for future customer-facing modules; cannot enter the staff panel.

Public registration is intentionally not enabled in this phase. Create staff accounts using the standard Laravel user tooling, assign the intended role securely, and never commit production credentials or `.env` files.

## Database and pricing notes

Laravel migrations are the canonical schema. `database/ideban_mysql.sql` is supplied for manual MySQL import into an **empty** database. Seed data creates service categories, service catalog placeholders and three quote-only plan tiers; it does not create fake portfolio entries or unverified official rates.

The admin price-record form keeps official, company-suggested, negotiated and quote-only price types distinct. To publish an official rate, supply its source, mark it verified, set its validity period and explicitly enable amount display. Unconfirmed amounts remain hidden from the public pricing page.

## Tests

The PHPUnit configuration uses an in-memory SQLite database for test runs. Run:

```sh
vendor/bin/phpunit
```

The feature tests cover the public RTL landing page, lead persistence, suppression of unverified official prices, role-protected administration and service creation.

## Remaining phases

This delivery implements the approved first phase. Blog/video publishing and lead reports, customer accounts/orders/invoices/payments/support tickets, and protected paid-course delivery/progress are later phases. No payment gateway is configured or implied.
