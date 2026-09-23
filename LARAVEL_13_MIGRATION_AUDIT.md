# Laravel 13 Migration Audit

Audit date: 2026-09-23. Read-only: no application code was changed for this document.

## 1. Baseline

| Item | Finding |
|---|---|
| Laravel | 13.32.0 installed (`composer.json` `^13.0`); 13.33.0 available |
| PHP | 8.3.30 (`^8.3`) |
| Structure | Already on the Laravel 11+ skeleton: `bootstrap/app.php` with `withRouting/withMiddleware/withExceptions`, `bootstrap/providers.php`, no `Kernel`, no `RouteServiceProvider` |
| Packages | guzzle 7.15 (8.2 available), sanctum 4, tinker 3, pint, phpunit 11.5 (12.5 available), collision, ignition, sail |
| Frontend | Vite 5, `laravel-vite-plugin` ^1, Tailwind 3.4, axios. No `resources/views` build step is relied on by tests |
| Security advisories | `composer audit`: none |
| Tests | 69 feature tests, all passing; `tests/Unit` holds only the stock `ExampleTest` |
| Static analysis | None configured (no Larastan/PHPStan) |
| Formatting | Pint installed, no `pint.json`; `pint --test` fails on ~35 files |

**Conclusion: the framework upgrade itself is already done.** Phase 2 (framework compatibility) reduces to the small items below. The remaining work is convention and quality cleanup, not a migration.

## 2. Architecture snapshot

- Controllers: `Auth/*`, `Admin/{Admin,Dashboard,ManageProperty,UserManagement}`, `CustomerController`, `PropertyFavorite`, `PropertyInterest`, `UserDashboard`. Largest are `ManagePropertyController` (241 lines) and `UserManagementController` (221). No controller is a fat business workflow.
- Services: `AuthService`, `RegistrationService`, `VerificationService`, `PropertyService` (362 lines), `PropertyMediaService`. Reasonable; `PropertyService` mixes listing/search/recommendations/cache/detail and is the one candidate to split.
- Requests: 14 Form Requests (Admin/Auth). All controllers validate through them; there are no inline `->validate()` calls.
- Models: 21. Lookup tables (`AccreditationStatus`, `ExperienceLevel`, ...) plus property graph. No policies, no enums, no API resources, no jobs/events/listeners, no repositories (correct: none are needed).
- Guards: `web` (investors) and `admin` (`Admin` model), admin routes gated by `AdminAuthAccess` middleware.

## 3. Findings

### 3.1 Framework / Laravel 13 compatibility (low risk)
1. `composer.json` autoload `files` entry loads `vendor/symfony/polyfill-php86/.../SortDirection.php`. This is a workaround pointing into `vendor`, fragile and unexplained. Verify what needs it and remove or replace with a proper dependency.
2. `composer.json` `dev` script contains mis-indented JSON and runs `queue:listen --tries=1`; `config.platform-check: false` hides PHP/extension mismatches on deploy. Decide whether to re-enable.
3. `routes/api.php` contains only the stock Sanctum `/user` closure; Sanctum (`HasApiTokens`, `config/sanctum.php`) is otherwise unused. No real API exists, so API Resources/response standardization (brief sections 13 and 25) is not applicable today.
4. `bootstrap/app.php` `withExceptions` is empty; no custom exception rendering. Acceptable.
5. Vite 5 / Tailwind 3 are outdated but not framework-blocking. Upgrading is a frontend decision, not part of Laravel 13 conformance.

### 3.2 Security
1. `Route::match(['get','post'], '/logout')` allows logout via GET (CSRF-able "logout CSRF"), and admin logout is also GET (`/admin/logout`). Restrict to POST (needs view/link changes).
2. `.env.example` previously carried a real admin password (blanked in commit `7a47554`); it remains in git history. **Rotate that password.**
3. No policies/gates: authorization is guard-level only (admin vs investor), which is adequate for the current two-role model. Property/interest/favorite actions operate on the authenticated user's own rows; no IDOR seen, but this is worth an explicit test.
4. Rate limiting exists for both login flows (via Form Requests) but not for registration, forgot-password or the favorite/interest POSTs.
5. Uploads: validated by Form Requests (image/mimes/max 2048 KB, PDF documents) and stored through `Storage`. Verify MIME sniffing coverage (existing tests exercise fake PHP-in-PNG).
6. Private bucket: images use `Storage::url()`, which 403s on a private S3 bucket. This is the owner's accepted current behavior; a signed-URL or CloudFront decision is still open (see debt).

### 3.3 Performance / database
1. `PropertyService::getFilteredProperties` writes slugs to rows while paginating (a write in a read path). Backfill once (there is already `GeneratePropertySlugsCommand`) and remove the loop.
2. Keyword search uses `LIKE %kw%` on `name`/`description` and address columns; cannot use indexes. Fine at current size; note for scale.
3. `properties.slug` has a plain index, not a **unique** index, although the model guarantees uniqueness in code. Add a unique index in a new migration once duplicates are ruled out.
4. Listing/detail caches use the `database` cache store with a version-bump invalidation; sound, but the store is hardcoded as a constant instead of configuration.
5. N+1: listing, recommendations, user dashboard and admin list already eager-load. `admin/users/index` and `show` call `->count()` on relations; confirm they are eager-loaded (`withCount` would be cheaper).
6. Filters `whereHas` on `details`/`address` per request; acceptable, indexes exist on `city`, `state`, `type`, `bedrooms`, `baths`, `value`.

### 3.4 Structure and naming
1. Duplicate model `Admin` and `Admins` (`Admins` is an alias still used by seeders, one factory and a console command). Consolidate on `Admin`.
2. Model names `PropertyModel`, `PropertyImageModel`, `PropertyDocumentModel` deviate from Laravel naming (`Property`, `PropertyImage`, `PropertyDocument`). Renaming touches most of the codebase; low value, high churn. **Recommend leaving.**
3. Duplicate relation aliases on `PropertyModel` (`images/propertyImage`, `details/propertyDetails`, `address/propertyAddress`, `offering/propertyOffering`, `documents/propertyDocumentModel`). Pick one set.
4. Route names/URLs mix styles: `/property_singlepage`, `property_singlepage` route name, `CustomerController::property_singlepage` (snake_case method), `investor` action behind `/invest`. Legacy `/property_singlepage?id=` redirects to the slug URL and is covered by a test.
5. Admin wizard routes use `{id}` strings and manual `findOrFail` instead of route model binding; the resource routes use `string $id` too.
6. `Route::view('/investment-interest', ...)` and other routes use fully-qualified controller class names inline instead of imports.
7. Magic strings: property `availability` (`'Available'`, `'Not Available'`) appears in views, the console command and seeders. Candidate for a backed enum (`PropertyAvailability`).
8. No `declare(strict_types=1)` anywhere (62 of 62 app files lack it). Optional; adopting it is low risk but touches every file.
9. Two one-off maintenance commands remain (`properties:migrate-availability`, `GeneratePropertySlugsCommand`). Keep only while the data migration is still pending.

### 3.5 Blade
1. Remaining logic in views is presentational (filters, badges). The queries and image URL building found earlier have been moved to controllers/models (commit `394011d`).
2. Repeated markup (cards, stepper nav, form fields) is a component opportunity but not required.

### 3.6 Configuration
1. No `env()` calls outside `config/`. Good.
2. `PropertyService::CACHE_STORE = 'database'` hardcodes a store name instead of `config('cache.default')`.
3. `config/auth.php` holds `admin_cred` (seed credentials) inside the auth config; fine for seeding, but it belongs in a seeder/`services` config, not auth.
4. `.env.example` now matches the runtime (`CACHE_STORE`, `QUEUE_CONNECTION=database`).

### 3.7 Testing
- 69 feature tests cover auth, listing, single page, admin CRUD/wizard, uploads and hostile input. They run against the `laravel_on_aws_test` MySQL database, which must be migrated and lightly seeded (an admin row and lookup tables) before the suite passes; this setup is not scripted anywhere.
- Gaps: no unit tests for `PropertyService` filter/sort/recommendation logic, no test for favorites/interest ownership rules beyond basics, no test for the new accessors' edge cases beyond one slug-page test.

### 3.8 Code quality
- Pint fails on ~35 files; most are `line_ending` (CRLF working copies) and `ordered_imports`/`not_operator_with_successor_space` style. Add `pint.json` (Laravel preset) and normalize line endings with `.gitattributes` (`* text=auto eol=lf`) before running Pint, otherwise every commit produces noise.
- No static analysis. Recommend adding Larastan at a moderate level with a baseline.

### 3.9 Documentation
- `README.md` is a single title line. Needs: requirements, install, `.env` setup (S3, queue, cache), test database setup, deploy steps (`migrate --force`, `config:cache`, `route:cache`, `view:cache`, `queue:work`).

## 4. Proposed phases (for approval)

**Phase 2 – Framework compatibility (small):** update to 13.33; investigate/remove the polyfill autoload hack; decide on `platform-check`; add `.gitattributes` and `pint.json`.

**Phase 3 – Standardization, module by module, tests first:**
1. Auth/Admin: logout to POST-only, rate-limit register/forgot-password, consolidate `Admin`/`Admins`.
2. Property module: `PropertyAvailability` enum, remove alias relations, slug backfill + unique index (new migration), remove write-in-read slug loop, split `PropertyService` (listing/search vs recommendations) only if it stays hard to follow.
3. Admin wizard: route model binding, keep the step workflow (not turned into CRUD).
4. Users module: `withCount`, `dob` cast with correct form value formatting.
5. Cache store from config.
6. Delete one-off commands after the data migration is confirmed.

**Phase 4 – Tests:** unit tests for `PropertyService` filters/sorts/recommendations; ownership tests for favorites/interest; scripted test-DB setup (`composer test` or a seeder).

**Phase 5 – Quality:** Pint across the repo after line-ending normalization; Larastan with a baseline.

**Phase 6 – Final audit and README.**

## 5. Decisions needed from the owner
1. Logout via POST only breaks any `<a href="/logout">` links; OK to change links to forms?
2. Keep the model names `PropertyModel` etc., or accept a large rename?
3. Add `declare(strict_types=1)` repo-wide?
4. Private S3 images: keep 403 for now, or move to signed URLs/CloudFront?
5. Add Larastan (new dev dependency)?
6. Is the database deployed anywhere with real data? (This decides new-migration-only vs rewriting old migrations; earlier this session old migrations were edited for the removed modules on the assumption of a dev-only database.)
