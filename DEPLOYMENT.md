## Deployment

These steps outline a typical production deployment for this Laravel app.

### Requirements
- PHP 8.2+
- Web server (Nginx/Apache) with PHP-FPM
- Composer
- Node 18+
- Database (SQLite/MySQL/Postgres)
- Redis or database-backed sessions/queues (optional)

### 1) Provision environment
- Create a system user and app directory, e.g., `/var/www/cms-project`.
- Ensure the web server user can read the app and write to `storage/` and `bootstrap/cache/`.

### 2) Fetch code
```bash
cd /var/www/cms-project
git clone <repo-url> .
composer install --no-dev --prefer-dist --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

### 3) Configure environment
Set at minimum in `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Example: SQLite
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/cms-project/database/database.sqlite

# Example: MySQL
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=cms
# DB_USERNAME=cms
# DB_PASSWORD=strong-password
```

### 4) Build assets
```bash
npm ci
npm run build
```

### 5) Database and storage
```bash
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"   # if using SQLite
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

### 6) Optimize framework caches
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 7) Configure web server
- Document root: `public/`
- Deny access to everything outside `public/`
- Ensure `index.php` front controller is used

Example Nginx location block:
```nginx
root /var/www/cms-project/public;
index index.php;

location / {
  try_files $uri $uri/ /index.php?$query_string;
}

location ~ \.php$ {
  include fastcgi_params;
  fastcgi_pass unix:/run/php/php8.2-fpm.sock;
  fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
}
```

### 8) Queue worker and scheduler (optional)
- Supervisor systemd unit for queue worker, if you add queued jobs:
```bash
php artisan queue:work --sleep=3 --tries=3 --max-time=3600
```
- Add cron for scheduler:
```
* * * * * cd /var/www/cms-project && php artisan schedule:run >> /dev/null 2>&1
```

### 9) Zero-downtime deploys (optional)
- Use symlink-based releases or a tool like Envoy/Deployer.

### 10) Post-deploy
- Smoke test `https://your-domain.com`
- Create an admin user if needed via tinker or seeder.


