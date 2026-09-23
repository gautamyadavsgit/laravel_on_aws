# Gautam Real Estate

Fractional real-estate investment platform built on Laravel 13 (PHP 8.3+).

- **Investors** browse listings (`/invest`), view a property (`/property/{slug}`), favorite properties, register interest and manage their profile.
- **Admins** (`/admin`) manage properties through a five-step wizard (listing, address, specs, offering, documents) and manage users.
- Property media (images, documents) is stored through Laravel's `Storage` on the default disk (`local`/`public` in development, S3 in production).

## Requirements

- PHP 8.3+, Composer 2
- MySQL 8 (or MariaDB 10.6+)
- Node 18+ (Vite/Tailwind build)

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
# set DB_*, ADMIN_EMAIL and ADMIN_PASSWORD in .env
php artisan migrate --seed
npm install && npm run build
php artisan storage:link
php artisan serve
```

`php artisan migrate --seed` creates the admin account from `ADMIN_EMAIL` / `ADMIN_PASSWORD`, the lookup tables and sample properties. For a large data set, `php artisan property:seed-million --count=1000`.

Run the queue worker and the Vite dev server alongside the app during development: `composer dev`.

## Configuration

| Variable | Purpose |
|---|---|
| `FILESYSTEM_DISK` | `local` in development, `s3` in production |
| `AWS_*` | S3 credentials/bucket/region (required when `FILESYSTEM_DISK=s3`) |
| `CACHE_STORE` | `database` (property listing/detail caches; `cache` table) |
| `QUEUE_CONNECTION` | `database` (`jobs` table); run `php artisan queue:work` |
| `ADMIN_EMAIL`, `ADMIN_PASSWORD` | Credentials used by the admin seeder |
| `MAIL_*` | Verification and password-reset emails |

Application code reads configuration through `config()`, never `env()`.

### Private S3 buckets

Media URLs are built with `Storage::url()`. With a private bucket these URLs return `403`; images only render if the bucket allows public reads (bucket policy or CloudFront). Signed URLs are intentionally not used yet.

## Testing

The suite runs against a real MySQL database named in `.env.testing` (default `laravel_on_aws_test`). It is separate from your development database.

```bash
# one-time (and after migration changes)
php artisan migrate:fresh --env=testing --force
php artisan db:seed --class=LookupTablesSeeder --env=testing --force

composer test      # php artisan test
composer lint      # Laravel Pint (style check)
composer analyse   # Larastan level 5
```

Tests that create data use `DatabaseTransactions` so they leave the database clean.

## Deployment

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan queue:work --tries=3   # run under a process manager
```

Set `APP_ENV=production`, `APP_DEBUG=false` and `LOG_LEVEL=warning`. Rotate the admin password if it was ever committed or shared.

## Structure notes

- `app/Http/Controllers` are thin; validation lives in `app/Http/Requests`.
- `PropertyService` owns listing, filtering, detail lookups and the property caches; `PropertyRecommendationService` logs searches and recommends properties; `PropertyMediaService` owns uploads.
- Admin property management is `Admin\PropertyController` (CRUD) plus one small controller per wizard step (`PropertyAddressController`, `PropertyDetailController`, `PropertyOfferingController`, `PropertyDocumentController`); public browsing is `PropertyController`.
- `app/Enums/PropertyAvailability` is the only fixed-state enum today (`Available`, `Not Available`; database values unchanged).
- Presentation helpers live on the models (`hero_image_url`, `gallery_urls`, `location_label`, `url`); Blade views do not query the database.
