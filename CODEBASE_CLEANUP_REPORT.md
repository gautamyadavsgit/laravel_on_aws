# Codebase Cleanup Report

Scope: naming, file organisation, dead/abandoned code, dependencies and test structure across the whole application.
Baseline: commit `af6d51b` (before this cleanup). No business rules were changed; behaviour changes are listed explicitly at the end.

## Files renamed

| Old | New |
|---|---|
| `app/Models/PropertyModel.php` | `app/Models/Property.php` |
| `app/Models/PropertyImageModel.php` | `app/Models/PropertyImage.php` |
| `app/Models/PropertyDocumentModel.php` | `app/Models/PropertyDocument.php` |
| `app/Models/PropertyDetails.php` | `app/Models/PropertyDetail.php` |
| `database/factories/PropertyDataFactory.php` | `database/factories/PropertyFactory.php` (rewritten with relation states) |
| `database/factories/PropertyDetailsFactory.php` | `database/factories/PropertyDetailFactory.php` |
| `app/Http/Controllers/CustomerController.php` | `app/Http/Controllers/PropertyController.php` |
| `app/Http/Controllers/Admin/ManagePropertyController.php` | `app/Http/Controllers/Admin/PropertyController.php` |
| `app/Http/Controllers/Admin/AdminController.php` | `app/Http/Controllers/Admin/AdminAuthController.php` |
| `app/Http/Controllers/Admin/UserManagementController.php` | `app/Http/Controllers/Admin/UserController.php` |
| `app/Http/Middleware/AdminAuthAccess.php` | `app/Http/Middleware/EnsureAdminAuthenticated.php` |
| `app/Http/Requests/Admin/AdminAddPropertyRequest.php` | `StorePropertyRequest.php` |
| `app/Http/Requests/Admin/AdminUpdateAddressRequest.php` | `UpdatePropertyAddressRequest.php` |
| `app/Http/Requests/Admin/UpdatePropertyDetailsRequest.php` | `UpdatePropertyDetailRequest.php` |
| `app/Http/Requests/Auth/InvestorRegisterRequest.php` | `RegisterRequest.php` |
| `app/Http/Requests/Auth/PasswordResetEmailRequest.php` | `SendPasswordResetLinkRequest.php` |
| `app/Http/Requests/Auth/PasswordResetUpdateRequest.php` | `ResetPasswordRequest.php` |
| `database/seeders/CreateAdminUser.php` | `AdminSeeder.php` |
| `database/seeders/ImageSeederHelper.php` | `SeedAssetGenerator.php` |
| `resources/views/admin/properties/{properties, add_property, edit_properties, property}` | `{index, create, edit, show}` |
| `resources/views/admin/properties/{edit_address, edit_details, edit_property_offering, edit_property_documents}` | `edit-address`, `edit-details`, `edit-offering`, `edit-documents` |
| `resources/views/frontend/properties.blade.php` / `property_singlepage.blade.php` | `frontend/properties/index` / `frontend/properties/show` |
| `resources/views/frontend/user_dashboard` / `user_profile` | `frontend/user/dashboard` / `frontend/user/profile` |
| `resources/views/frontend/investment_interest` | `frontend/investment-interest` |
| `resources/views/register.blade.php` | `resources/views/auth/register.blade.php` |
| `resources/views/emails/{reset_password, verify_email}` | `emails/{reset-password, verify-email}` |
| `tests/Feature/AuthTest.php`, `ExampleTest.php` | `AuthenticationTest.php`, `HomePageTest.php` |

Convention adopted: multi-word view files are kebab-case; PHP files are PascalCase matching the class; model names are singular.

## Files moved / created

- Admin wizard split out of the 240-line controller into `Admin\PropertyAddressController`, `PropertyDetailController`, `PropertyOfferingController`, `PropertyDocumentController` (each `edit`/`update`).
- `app/Http/Requests/ListPropertiesRequest.php` (listing filter sanitising moved out of the controller).
- `app/Http/Requests/Concerns/ThrottlesLoginAttempts.php` (shared login throttling).
- `app/Services/PropertyRecommendationService.php` (search logging + recommendations, split from `PropertyService`).
- `tests/DatabaseTestCase.php` (shared base for DB tests) and the split test classes listed below.

## Classes renamed

`PropertyModel`→`Property`, `PropertyImageModel`→`PropertyImage`, `PropertyDocumentModel`→`PropertyDocument`, `PropertyDetails`→`PropertyDetail`, `CustomerController`→`PropertyController`, `ManagePropertyController`→`Admin\PropertyController`, `AdminController`→`AdminAuthController`, `UserManagementController`→`UserController`, `AdminAuthAccess`→`EnsureAdminAuthenticated`, `CreateAdminUser`→`AdminSeeder`, `ImageSeederHelper`→`SeedAssetGenerator`, plus the request classes above. The `admin.access` middleware alias is unchanged.

## Methods / routes / variables renamed

- `CustomerController::investor` → `PropertyController::index`; `property_singlepage` → `show` (slug) and `showLegacy` (`/property_singlepage?id=`).
- `DashboardController::viewDashboard` → `__invoke`.
- Relation names on `Property`: one set only (`images`, `address`, `details`, `offering`, `documents`); the duplicate `property*` aliases were removed earlier and the `propertyModel()` aliases on child models are gone.
- Route names: `properties`→`properties.index`, `property.singlepage`→`properties.show`, `property_singlepage`→`properties.legacy`, `property.favorite.toggle`→`properties.favorite`; `manage-property.*` and `admin.manage-property.*`→`admin.properties.*` (`address|details|offering|documents` × `edit|update`).
- Admin URLs: `/admin/manage-property[...]` → `/admin/properties[...]`, wizard steps `/admin/properties/{property}/{address|details|offering|documents}` (PUT to update; the forms send `@method('PUT')`).
- Vague variables: `$data` → `$validated` / `$attributes` / `$credentials`; view variables `property` (paginator) → `properties`, `featuredProperty` → `property`; wizard views read `$property->address|details|offering|documents` instead of separate view variables.

## Dead code removed

- **Views (5):** `admin/marketplace`, `admin/messages`, `admin/transaction`, `admin/users/registration`, `welcome` (no route or reference).
- **Routes/config:** `routes/api.php` (Sanctum stub), `routes/channels.php` (never registered), `config/broadcasting.php`, `config/sanctum.php`, `config/cors.php`, `config/view.php` and `config/hashing.php` (identical to framework defaults), the `api` rate limiter, `AppServiceProvider::HOME`, Pusher/Vite/Broadcast env vars.
- **Frontend:** `resources/js/app.js` + `bootstrap.js` (not a Vite entry), the whole `resources/scss` tree (no sass, never imported), `.alert-warning-tw`, the `.vue` Tailwind glob; assets rebuilt (`public/build`).
- **PHP:** `InvestorLoginRequest` (empty subclass), `LoginRequest::authenticate()`, `Admins` alias model, `Property`/`PropertyImage`/`PropertyDocument` alias subclasses, five `propertyModel()` aliases, unused inverse relations (`property()` on five child models, `Property::admin`, `Admin::properties`, `PropertyFavorite/PropertyInterest/UserSearchLog::user`), `PropertyService::getPaginatedProperties`, `PropertyMediaService::deleteFile`, two one-off maintenance commands, the composite `PropertyDataFactory` and the three child factories nothing used.
- **Translations:** 170 of 314 keys in `resources/lang/en/*` (dynamic `__($status)` uses the framework keys; `validation.php` left intact).
- **Root `.htaccess`:** byte-identical to `public/.htaccess`, no root front controller.
- **Tests:** stock `Unit/ExampleTest` (`assertTrue(true)`) and the empty Unit suite in `phpunit.xml`.
- Nothing commented-out or debugging-related was found (`dd`, `dump`, `var_dump`, `print_r`, `console.log`, `debugger`, commented PHP/JS/Blade); the only `alert()`/`console.error` calls are intentional UX in `frontend.js`.

## Dependencies removed

- `laravel/sanctum` (only a stub `/user` route and `HasApiTokens`; no API exists)
- `laravel/sail` (no docker-compose anywhere)
- `guzzlehttp/guzzle` as a root requirement (still installed: `laravel/framework` requires it)
- npm `axios` (only the deleted bootstrap.js used it), `@tailwindcss/forms` (not in `tailwind.config.js` plugins)
- Kept on purpose: `mockery`, `collision`, `ignition`, `faker`, `tinker` (framework/dev tooling).

## Duplicate code removed

- Login throttling duplicated in `LoginRequest` and `AdminLoginRequest` → `ThrottlesLoginAttempts` trait.
- The if/else "find or create" blocks for address/details/offering → `hasOne(...)->updateOrCreate([], $validated)`.
- Repeated property construction in tests → `PropertyFactory` states and `DatabaseTestCase` helpers.
- Listing filter sanitising and paginated eager-loading definitions live in one place each.

## Debug code removed

None present in application code (see above).

## Tests

- Before: 83 tests, 631 assertions. After: **82 tests, 630 assertions, all passing** (the removed test was the stock `assertTrue(true)`).
- The 1,000-line `AuditNegativePathTest` was split into `InvestorLoginTest`, `InvestorRegistrationTest`, `PasswordResetTest`, `EmailVerificationTest`, `PropertyBrowsingTest`, `PropertyFavoriteAndInterestTest`, `AdminAccessTest`, `AdminPropertyManagementTest`, `AdminUserManagementTest` (same test bodies, same names).
- `AuthenticationTest`, `AdminUserCrudTest`, `PropertyPaginationTest`, `PropertySlugTest`, `SoftDeletedPropertyTest` and the others now extend `DatabaseTestCase`, so they are transaction-wrapped and no longer depend on pre-existing rows (`User::first()`, `Admin::first()`).

## Static analysis / formatting

- Laravel Pint (`pint --test`): passes. Larastan level 5, no baseline: `[OK] No errors`. `npm run build`: succeeds.
- A repository-wide check confirms every `namespace` matches its path, every `use App|Database|Tests` import resolves (case-sensitive), every referenced view exists and every `route('...')` name exists.

## Behaviour changes (intentional)

1. **URLs/route names** listed above (admin `manage-property` → `properties`, step URLs, PUT for wizard updates). Bookmarks to old admin URLs will 404; the public URLs `/invest`, `/property/{slug}` and `/property_singlepage?id=` are unchanged.
2. The user dashboard linked to `route('property.singlepage', ['id' => ...])`, which would throw a missing-parameter error as soon as a user had a favorite or interest; it now links by slug.
3. Admin lists and edit pages resolve properties through route model binding (missing/soft-deleted → 404, as before).

## Remaining technical debt (needs an owner decision)

- `user_alerts`: rows are written on registration (and seeded) but nothing reads or edits them. Either build the alert-preferences feature or drop the table, the insert in `RegistrationService`, and the seeder block.
- `personal_access_tokens` migration remains (historical migrations are not deleted); with Sanctum gone the table is unused and could be dropped in a new migration.
- Column names with typos (`middile_name`, `experiance_level`, `square_feets`, `Documents_Expence_Calculations` keys) are part of the schema and were left; the application layer maps them.
- `PropertyInterest.status` only ever holds `'new'`; introduce an enum when a status workflow exists.
- `RegistrationService` maps form values to lookup-table primary keys with literal 1–4 maps; they must stay in sync with `LookupTablesSeeder`.
- `SeedMillionPropertiesCommand`/`MillionPropertySeeder` are named for 1,000,000 rows while the seeder seeds 100; rename if that stays a dev-only tool.
- `public/build` is tracked; consider ignoring it and building at deploy time.
- `composer.lock` is git-ignored, so dependency versions are not pinned between deploys.
- Redis/Memcached/SQL Server config blocks in `config/database.php` and `config/cache.php` are framework defaults for unused drivers.
