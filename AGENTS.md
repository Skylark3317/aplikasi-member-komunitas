# AGENTS.md — Aplikasi Member Komunitas (AMK)

## Stack

Laravel 13 + Vue 3 (Composition API) + Inertia.js SPA + Tailwind CSS 4 + Vite 8.

## Quick start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
# Dev (serves app, queue worker, and Vite concurrently):
composer dev
```

## Dev commands

| Command | What it does |
|---------|-------------|
| `composer dev` | Runs `php artisan serve`, `queue:listen`, and `npm run dev` concurrently |
| `composer test` | Runs `config:clear` then `php artisan test` (PHPUnit) |
| `npm run dev` | Vite dev server only |
| `npm run build` | Vite production build |

## Testing

- PHPUnit 12.x via `php artisan test` or `composer test`
- Feature tests use `RefreshDatabase` trait with in-memory SQLite
- Run a single test: `php artisan test tests/Feature/RbacTest.php`
- `composer test` always clears config first

## Architecture

- **No CI/CD**, no TypeScript, no ESLint/Prettier — only PHP linting via Laravel Pint
- `.npmrc` sets `ignore-scripts=true` (run `npm install --ignore-scripts` or omit the flag; scripts won't run)
- Session driver: `database` (needs `sessions` table, already in migrations)
- Queue driver: `database` — `php artisan queue:listen` must be running for async jobs (included in `composer dev`)
- JS path alias: `@/` → `resources/js/`
- Ziggy provides Laravel route names in JS via `route()` helper
- Vite entry: `resources/js/app.js` and `resources/css/app.css`

## Role system (RBAC)

5 roles defined in `app/Enums/UserRole.php`, enforced by `CheckRole` middleware.

| URL prefix | Middleware role | DB role value |
|-----------|----------------|---------------|
| `/superadmin/*` | `role:super_admin` | `super_admin` |
| `/ketua/*` | `role:ketua` (note: model value is `leader`) | `leader` |
| `/petugas/*` | `role:staff` | `staff` |
| `/keuangan/*` | `role:finance` | `finance` |
| `/member/*` | `role:member` | `member` |

Role enum values in DB: `member`, `staff`, `finance`, `leader`, `super_admin`.

## Default users (all password: `Password123`)

- superadmin@amk.com — Super Admin
- ketua@amk.com — Leader
- staff@amk.com — Staff
- bendahara@amk.com — Finance
- nem@amk.com — Member

## Scheduled tasks (defined in `routes/console.php`)

- `accounts:purge-expired` — daily, deletes accounts pending deletion for 7+ days
- `email:weekly-summary` — Mon 09:00, premium content summary
- `email:membership-expiring` — daily 08:00, expiring premium notices

## Notes

- PHP ^8.3 required
- db dev default: SQLite (`database/database.sqlite`)
- test db: `:memory:` SQLite (set in `phpunit.xml`)
- Password default in seeder is `Password123`, not `password`
- `CheckRole` middleware checks `auth()->user()->role` against the route requirement
- Controllers are separated by role in `app/Http/Controllers/{Role}/`
- Vue Pages are in `resources/js/Pages/{role}/`
