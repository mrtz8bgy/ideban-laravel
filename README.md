# Ideban Almas

Bilingual (Persian RTL / English LTR) IT services storefront and lead-management platform built on the existing Laravel 8 application.

## Phase-one features

- Responsive Persian and English public pages: homepage, service catalog and details, pricing, published portfolio, and consultation/quotation form.
- Server-side validated and rate-limited lead capture.
- Staff login and protected admin dashboard with role checks (`admin`, `content`, `sales`).
- Admin CRUD for service categories, services, plans, price records and sources, portfolio entries, journal articles and videos; sales staff can track lead stages, assignment, follow-up date, expected value, and notes.
- Official tariffs are excluded from public results unless their source has been marked verified. Amount display can be turned off independently.
- No sample client projects or official tariff amounts are seeded. Starter service names and quote-only plan labels are catalog placeholders, not claims about delivered projects or fixed prices.

## Requirements

- PHP 7.3+ (PHP 7.4 is supported by the current lock file), Composer, and MySQL 5.7+ or MariaDB with JSON column support.
- Node.js is not needed for the included frontend stylesheet.
- Internet access for Google Fonts (Vazirmatn, Cinzel) is optional; the site falls back to system fonts without it.

## Install

1. Clone/download the project and install dependencies:

   ```sh
   composer install
   ```

2. Create an empty MySQL database and copy `.env.example` to `.env`. Configure `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD`. Then generate the application key:

   ```sh
   php artisan key:generate
   ```

   Point the web server's document root to the project's `public` directory. Set `APP_URL` to the public base URL (for example `https://example.com`; include the actual subdirectory only if the app is intentionally served below the domain root). Application links and assets are generated from the current request, so they do not depend on the project folder's name.

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

   Open `/` for the public site, `/login` for staff and customers, and `/register` to create a customer account.

6. Make uploaded public media available:

   ```sh
   php artisan storage:link
   ```

   Catalog, portfolio, article, course, lesson-thumbnail and public-video uploads are stored on Laravel's `public` disk under `storage/app/public/media` and served through `public/storage`. The storage link is required after deployment. Public images are limited to 5 MB; public video uploads accept MP4/WebM up to `PUBLIC_VIDEO_MAX_MB` (default 256 MB). Academy lesson videos are stored privately at `storage/app/academy/videos`, referenced by `lessons.file_path` and streamed only through the protected lesson route. The academy upload limit is the lowest of `ACADEMY_MAX_VIDEO_MB`, PHP's `upload_max_filesize`, and `post_max_size` (with 8 MB reserved for form/request overhead); the admin form displays this effective limit. Raise PHP's upload/post limits and the application setting together to accept larger files.

## Default administrator (SQL import)

The SQL dump creates the account **username `admin`** with password `IdebanAlmas#Gold2026`. Sign in at `/login` and change the password immediately. The account can also sign in with its email `admin@ideban.local`.

To create the account through the seeder instead, set a password in the environment and run the seeders:

```sh
IDEBAN_ADMIN_PASSWORD='choose-a-long-password' php artisan db:seed
```

Sample portfolio entries and two leads are labelled as demo data. Delete or replace them before going live.

## Roles

- `admin`: full admin panel access, including orders, invoices, payments, tickets, customers, courses and discount codes.
- `content`: service catalog, pricing, add-ons, portfolio, journal, video, courses, lessons and discount codes.
- `sales`: leads, orders, invoices, payment review, support tickets and customer records.
- `customer`: self-registered at `/register`; uses the customer panel at `/account` and cannot enter the staff panel.

Create staff accounts with `php artisan ideban:make-admin` or standard Laravel user tooling, assign the intended role securely, and never commit production credentials or `.env` files.

## Database and pricing notes

Laravel migrations are the canonical schema. `database/ideban_mysql.sql` is supplied for manual MySQL import into an **empty** database. Seed data creates service categories, service catalog placeholders and three quote-only plan tiers; it does not create fake portfolio entries or unverified official rates.

The admin price-record form keeps official, company-suggested, negotiated and quote-only price types distinct. To publish an official rate, supply its source, mark it verified, set its validity period and explicitly enable amount display. Unconfirmed amounts remain hidden from the public pricing page.

## Tests

The PHPUnit configuration uses an in-memory SQLite database for test runs. Run:

```sh
vendor/bin/phpunit
```

The feature tests cover the public RTL landing page, lead persistence, suppression of unverified official prices, role-protected administration, service creation, unpublished/future journal entries, escaping of article HTML, the video host whitelist and content-manager publishing. Phase three adds tests for server-side calculator pricing, the honeypot, the order-to-payment flow (quote, invoice, receipt, approval), enrollment after bank confirmation, protected video streaming, the customer panel's access rules, ticket creation, admin course uploads, and English-locale rendering.

## Phase two (delivered): journal and video center

- **Journal (`/blog`)**: categories, tags, search, cover image, author, publish date, SEO title and meta description, related articles, linked service, automatic table of contents for long articles. Body text is Markdown-lite (`## ` starts a section); raw HTML is escaped.
- **Video center (`/videos`)**: categories, search, thumbnail, duration, description, related videos and linked service. Only YouTube, Vimeo and Aparat links over `https` are accepted; the page embeds the host's privacy-enhanced player, not the raw link.
- Admin forms also accept image uploads for catalog categories, services, plans, add-ons, portfolio, articles, courses and lesson thumbnails, plus MP4/WebM files for public video entries. Existing image/video URLs remain supported where applicable.
- Admin management for both under **Admin → Articles / Videos** (roles `admin` and `content`).
- Sitemap includes published articles and videos; `robots.txt` is generated by the application (the static file was removed so the dynamic rule with the sitemap is used).
- Visual theme: black background with gold gradient headings and CTAs, Montserrat uppercase navigation with Vazirmatn for Persian text, pill-shaped gold buttons, hexagon texture, gold ring artwork, RTL-aware and responsive for mobile, tablet and desktop.
- Login accepts a username or an email address.
- Demo content: three labelled sample portfolio entries, two sample articles with cover images, and two sample leads.

## Phase three: calculator, customer panel, payments, academy

- **Cost calculator (`/calculator`)**: choose a service, plan and add-ons. The server recalculates every amount from the database, ignoring any amounts sent by the browser. Only company-suggested or negotiated amounts are priced; everything else is listed as **استعلام قیمت** (price inquiry). Guests can request a quote, which creates a lead (source `calculator`). Logged-in customers also get an order with status `requested`.
- **Customer panel (`/account`)**: dashboard, profile, orders with progress, invoices, purchased courses, and support tickets with attachments. Customers can see only their own records.
- **Orders and invoices (admin, `sales` and `admin`)**: update order status (requested, quoted, accepted, in progress, completed, cancelled) and progress. Issue invoices from staff-entered line items, with extra costs and validity days. Invoice numbers are generated automatically.
- **Payments**: `PAYMENT_GATEWAY=bank` (default) takes card-to-card transfers. The customer uploads a receipt (JPG, PNG or PDF, up to 5 MB); staff approve or reject it under **Payments & receipts**. Approval marks the invoice paid once the paid amount covers the total. `zarinpal` verifies every return on the server before anything is recorded as paid. The invoice is never marked paid from the browser.
- **Support tickets**: customers open tickets, staff reply, change status, priority and assignee. Attachments are stored privately.
- **Academy (`/academy`)**: course catalogue with category and price filters, course pages, and lesson pages. Free courses are added immediately. Paid courses create an invoice (discount codes supported), and access is granted only after the payment is confirmed.
- **Protected video**: uploaded lesson videos are stored in private storage (`storage/app/academy/videos`) and streamed only to enrolled users (or to anyone for lessons marked as free preview). Video files are never served from `public/`. YouTube and Vimeo links are embedded through the privacy-enhanced player.
- **Admin course tools**: create and edit courses, add lessons, upload videos (MP4 or WebM, up to the effective limit shown on the lessons page), set free previews, and manage discount codes under **Admin → Courses & video** and **Discount codes**.

### Upload limits for academy videos

`ACADEMY_MAX_VIDEO_MB` (default 1024) is the application's upper limit; the active upload limit is automatically reduced to match PHP's `upload_max_filesize` and `post_max_size`. The lessons page shows the effective limit. To accept larger files, raise both PHP values and this setting, and raise `max_execution_time` if uploads time out. Web servers have their own limits too (for example `client_max_body_size` in nginx).

### Sample data

The seeders add two sample courses, three sample add-ons and the sample discount code `SAMPLE10`. Every item is labelled "نمونه" / "Sample". No video files are attached, and add-ons are quote-only (no amounts). The lessons are placeholders until you upload real videos.

### Still to build lastupdate

Contracts, user and role management screens, sales reports, company and SEO settings screens. The ZarinPal amount unit (`ZARINPAL_AMOUNT_MULTIPLIER`) must be verified against your merchant account before live payments are enabled.
