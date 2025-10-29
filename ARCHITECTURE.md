## Architecture

### Overview
This is a monolithic Laravel application that provides a simple CMS for client users to create, edit, theme, publish, and track analytics for a single marketing page.

### Core components
- **Routes**: `routes/web.php` defines guest auth endpoints, protected dashboard/page management, analytics, and public page viewing.
- **Controllers**:
  - `AuthController`: Registration, login, logout. Auto-creates a default Page for new clients.
  - `DashboardController`: Shows a client’s page summary and analytics.
  - `PageController`: CRUD, publish/unpublish, image uploads, version listing.
  - `PublicPageController`: Serves published pages and tracks views.
  - `AnalyticsController`: Time-series analytics and CSV export.
  - `ThemeController`: Theme listing and preview JSON.
- **Models**:
  - `User` (roles: `client`, `admin`), 1:1 `Page`.
  - `Page` (JSON `content`, theme, publish flags) with relations to `User`, `Theme`, `PageView`, `PageVersion`.
  - `PageVersion` (immutable snapshots of content/theme with author and description).
  - `PageView` (per-visit analytics with unique-visitor flagging).
  - `Theme` (colors, preview image).
- **Policies**: `PagePolicy` authorizes view/update/delete to owner or admin; anyone authenticated can list.
- **Services**:
  - `PageService`: Page creation, update with versioning, publish/unpublish, image upload/delete, slug generation.
  - `AnalyticsService`: Track page views and compute daily/weekly/monthly aggregations; export data.

### Authentication and authorization
- Guard: `web` session auth (`config/auth.php`).
- Roles are stored on `users.role` (not from the Spatie tables by default in code paths).
- Authorization via `Gate::authorize` and `PagePolicy` for per-page access control.

### Data flow and interactions
1) Guest registers via `POST /register` → creates `User(client)` and a default `Page` via `PageService::createPage` → redirect to dashboard.
2) Authenticated client edits their `Page` via `PageController@edit/update`. On updates affecting `content/theme_id`, `PageService` writes a `PageVersion` snapshot.
3) Publish/unpublish toggles `Page.is_published` and optionally `published_at`.
4) Public page is served at `GET /page/{slug}` via `PublicPageController@show`. Each request is tracked as a `PageView`; unique is inferred by IP within 30 days.
5) Dashboard and analytics pages read aggregates from `AnalyticsService` and convenience methods on `Page`.

### Views and assets
- Blade templates in `resources/views` for login, register, dashboard, pages, analytics, public page, and themes.
- Vite builds `resources/css/app.css` and `resources/js/app.js`; Tailwind used via plugin.

### Background jobs and queues
- Queue scaffolding exists; current controllers/services perform synchronous operations. The dev script starts a queue listener to support future async work.

### Configuration and environment
- Default DB is SQLite (`database/database.sqlite`).
- Storage uploads use the `public` disk; ensure `php artisan storage:link` is run in all environments.

### Security considerations
- Passwords hashed via `Hash::make`.
- Auth session regeneration on login and token regeneration on logout.
- Authorization enforced on all page-mutating actions.

### Extensibility
- Add more Themes and expose customization fields within `Page.content`.
- Offload analytics tracking or exports to queued jobs if needed.
- Integrate the Spatie roles/permissions tables if fine-grained roles are required.


