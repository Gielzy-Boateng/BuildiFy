
## CMS Project

Lightweight Laravel-based CMS for single-page sites with themes, publishing, and built-in analytics.

### Tech stack
- Laravel 12 (PHP ^8.2)
- Blade views, Vite, Tailwind
- SQLite (default) or any Laravel-supported DB
- maatwebsite/excel, intervention/image

### Installation
1) Prerequisites
- PHP 8.2+, Composer, Node 18+, npm

2) Clone and bootstrap
```bash
git clone <your-repo-url> cms-project
cd cms-project
composer install
cp .env.example .env
php artisan key:generate
```

3) Database
- Default uses `database/database.sqlite`.
```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate --force
php artisan db:seed
```

4) Frontend assets
```bash
npm install
npm run build    # or: npm run dev
```

5) Storage symlink
```bash
php artisan storage:link
```

6) Run locally
```bash
php artisan serve
```
Visit `http://127.0.0.1:8000`.

### Local developer workflow
```bash
composer run dev
# Runs: php artisan serve, queue listener, logs, and Vite together
```

### Testing
```bash
php artisan test
```

### Environment variables (common)
- `APP_NAME`, `APP_ENV`, `APP_KEY`, `APP_URL`
- `DB_CONNECTION`, `DB_DATABASE` (SQLite by default)

### Key features
- User registration/login, role: `client`/`admin`
- Each client has one `Page`
- Version history for page updates
- Publish/unpublish workflow
- Theme selection and preview
- Public page at `/page/{slug}`
- Analytics tracking with export (CSV)

See `ARCHITECTURE.md`, `DATABASE_SCHEMA.md`, `DEPLOYMENT.md`, and `USER_GUIDE.md` for details.
