# SIPBAR V2 - Deployment Guide

Panduan deployment SIPBAR V2 ke production server.

## 📋 Pre-Deployment Checklist

### Server Requirements

- [x] PHP 8.2 atau lebih tinggi
- [x] MySQL 8.0 atau MariaDB 10.5+
- [x] Composer 2.x
- [x] Node.js 18+ & NPM
- [x] Web server (Nginx/Apache)
- [x] SSL Certificate (untuk HTTPS - required untuk camera access)
- [x] Cron daemon untuk scheduler

### PHP Extensions Required

```bash
php -m | grep -E 'pdo_mysql|mbstring|openssl|tokenizer|xml|ctype|json|bcmath|gd|zip'
```

Pastikan semua extension berikut terinstall:
- pdo_mysql
- mbstring
- openssl
- tokenizer
- xml
- ctype
- json
- bcmath
- gd (untuk QR code generation)
- zip

## 🚀 Deployment Steps

### 1. Clone Repository

```bash
cd /var/www
git clone <repository-url> sipbarv2
cd sipbarv2
```

### 2. Set Permissions

```bash
sudo chown -R www-data:www-data /var/www/sipbarv2
sudo chmod -R 755 /var/www/sipbarv2
sudo chmod -R 775 /var/www/sipbarv2/storage
sudo chmod -R 775 /var/www/sipbarv2/bootstrap/cache
```

### 3. Install Dependencies

```bash
# PHP dependencies
composer install --optimize-autoloader --no-dev

# JavaScript dependencies
npm ci
npm run build
```

### 4. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit environment file
nano .env
```

**Production .env configuration:**

```env
APP_NAME="SIPBAR V2"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://sipbar.sekolahanda.sch.id

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sipbarv2_prod
DB_USERNAME=sipbar_user
DB_PASSWORD=STRONG_PASSWORD_HERE

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Cache
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Queue (optional - for better performance)
QUEUE_CONNECTION=redis

# WhatsApp Integration
WA_BOT_URL=https://wa-bot.sekolahanda.sch.id
WA_BOT_API_KEY=your-production-api-key
WA_BOT_TIMEOUT=10

# Mail (optional - for password reset)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@sekolahanda.sch.id
MAIL_FROM_NAME="${APP_NAME}"
```

### 5. Database Setup

```bash
# Create database
mysql -u root -p
```

```sql
CREATE DATABASE sipbarv2_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sipbar_user'@'localhost' IDENTIFIED BY 'STRONG_PASSWORD_HERE';
GRANT ALL PRIVILEGES ON sipbarv2_prod.* TO 'sipbar_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (roles, permissions, sample users)
php artisan db:seed --force
```

### 6. Storage Link

```bash
php artisan storage:link
```

### 7. Optimize for Production

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Cache events
php artisan event:cache
```

### 8. Setup Cron Job

```bash
# Edit crontab sebagai www-data user
sudo crontab -u www-data -e

# Tambahkan baris ini:
* * * * * cd /var/www/sipbarv2 && php artisan schedule:run >> /dev/null 2>&1
```

Verify cron:
```bash
sudo crontab -u www-data -l
```

### 9. Setup Queue Worker (Optional)

Jika menggunakan queue untuk background jobs:

```bash
# Install Supervisor
sudo apt install supervisor

# Create supervisor config
sudo nano /etc/supervisor/conf.d/sipbarv2-worker.conf
```

**supervisor config:**

```ini
[program:sipbarv2-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/sipbarv2/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/sipbarv2/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Reload supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start sipbarv2-worker:*
```

## 🌐 Web Server Configuration

### Nginx Configuration

```bash
sudo nano /etc/nginx/sites-available/sipbarv2
```

**Nginx config:**

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name sipbar.sekolahanda.sch.id;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name sipbar.sekolahanda.sch.id;

    root /var/www/sipbarv2/public;
    index index.php index.html;

    # SSL Certificate
    ssl_certificate /etc/letsencrypt/live/sipbar.sekolahanda.sch.id/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/sipbar.sekolahanda.sch.id/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Logs
    access_log /var/log/nginx/sipbarv2-access.log;
    error_log /var/log/nginx/sipbarv2-error.log;

    # Max upload size (untuk QR code dan file attachment)
    client_max_body_size 10M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Cache static assets
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/sipbarv2 /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Apache Configuration (Alternative)

```bash
sudo nano /etc/apache2/sites-available/sipbarv2.conf
```

**Apache config:**

```apache
<VirtualHost *:80>
    ServerName sipbar.sekolahanda.sch.id
    Redirect permanent / https://sipbar.sekolahanda.sch.id/
</VirtualHost>

<VirtualHost *:443>
    ServerName sipbar.sekolahanda.sch.id
    DocumentRoot /var/www/sipbarv2/public

    SSLEngine on
    SSLCertificateFile /etc/letsencrypt/live/sipbar.sekolahanda.sch.id/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/sipbar.sekolahanda.sch.id/privkey.pem

    <Directory /var/www/sipbarv2/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/sipbarv2-error.log
    CustomLog ${APACHE_LOG_DIR}/sipbarv2-access.log combined
</VirtualHost>
```

```bash
# Enable required modules
sudo a2enmod rewrite ssl
sudo a2ensite sipbarv2
sudo systemctl reload apache2
```

## 🔒 SSL Certificate (Let's Encrypt)

```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate certificate
sudo certbot --nginx -d sipbar.sekolahanda.sch.id

# Auto-renewal test
sudo certbot renew --dry-run
```

## 🔍 Post-Deployment Verification

### 1. Check Application Status

```bash
# Check if site is accessible
curl -I https://sipbar.sekolahanda.sch.id

# Check PHP-FPM status
sudo systemctl status php8.2-fpm

# Check web server status
sudo systemctl status nginx  # or apache2
```

### 2. Test Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

### 3. Test QR Code Generation

```bash
# Check storage permissions
ls -la storage/app/public/qr-codes

# Test QR generation manually via tinker
php artisan tinker
>>> $request = App\Models\BorrowingRequest::first();
>>> app(App\Services\QRCodeService::class)->generateForRequest($request);
```

### 4. Test Scheduler

```bash
# Manual test
php artisan schedule:run

# Check schedule list
php artisan schedule:list
```

### 5. Test WhatsApp Integration

```bash
php artisan tinker
>>> app(App\Services\WhatsAppNotificationService::class)->checkBotStatus();
```

### 6. Monitor Logs

```bash
# Application logs
tail -f storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/sipbarv2-error.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

## 📊 Monitoring & Maintenance

### Daily Checks

```bash
# Check disk space
df -h

# Check database size
mysql -u root -p -e "SELECT table_schema AS 'Database', ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS 'Size (MB)' FROM information_schema.TABLES WHERE table_schema = 'sipbarv2_prod' GROUP BY table_schema;"

# Check failed jobs (if using queue)
php artisan queue:failed
```

### Weekly Maintenance

```bash
# Database backup
mysqldump -u sipbar_user -p sipbarv2_prod > /backup/sipbarv2_$(date +%Y%m%d).sql

# Clean old logs
php artisan log:clear --keep=30

# Clear old notifications (optional)
mysql -u sipbar_user -p sipbarv2_prod -e "DELETE FROM whatsapp_notification_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 90 DAY);"
```

### Performance Monitoring

```bash
# Check response time
ab -n 100 -c 10 https://sipbar.sekolahanda.sch.id/

# Check database queries (enable query log in .env for debugging)
# LOG_QUERY=true in .env
```

## 🔄 Updating Application

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Pull latest code
git pull origin main

# 3. Update dependencies
composer install --optimize-autoloader --no-dev
npm ci
npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear and rebuild caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Restart services
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

# 7. Disable maintenance mode
php artisan up
```

## 🆘 Rollback Procedure

Jika terjadi masalah setelah deployment:

```bash
# 1. Enable maintenance mode
php artisan down

# 2. Revert to previous version
git reset --hard <previous-commit-hash>

# 3. Rollback database (jika ada migration baru)
php artisan migrate:rollback --step=1

# 4. Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart services
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

# 6. Disable maintenance mode
php artisan up
```

## 📞 Troubleshooting

### Issue: 500 Internal Server Error

```bash
# Check logs
tail -f storage/logs/laravel.log

# Check permissions
sudo chown -R www-data:www-data /var/www/sipbarv2
sudo chmod -R 775 storage bootstrap/cache

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Issue: Queue not processing

```bash
# Check queue worker status
sudo supervisorctl status sipbarv2-worker:*

# Restart workers
sudo supervisorctl restart sipbarv2-worker:*

# Clear failed jobs
php artisan queue:flush
```

### Issue: Scheduler not running

```bash
# Check cron logs
grep CRON /var/log/syslog

# Manually test schedule
php artisan schedule:run

# Verify crontab
sudo crontab -u www-data -l
```

### Issue: QR Scanner tidak berfungsi

- Pastikan menggunakan HTTPS (required untuk camera access)
- Check browser console untuk error
- Verify camera permissions granted
- Test di browser berbeda (Chrome, Safari)

## 🔐 Security Best Practices

1. **Never commit .env file** ke repository
2. **Use strong passwords** untuk database dan API keys
3. **Keep dependencies updated** dengan `composer update` dan `npm update`
4. **Enable firewall** dan hanya buka port yang diperlukan
5. **Regular backups** database dan storage
6. **Monitor logs** untuk suspicious activity
7. **Rate limiting** sudah aktif di Laravel
8. **CSRF protection** sudah aktif untuk semua forms
9. **SQL injection protection** via Eloquent ORM
10. **XSS protection** via Blade templating auto-escaping

## 📝 Deployment Checklist

- [ ] Server requirements terpenuhi
- [ ] SSL certificate terinstall
- [ ] Database dibuat dan migrasi berhasil
- [ ] Environment variables dikonfigurasi
- [ ] Storage permissions correct
- [ ] Cron job untuk scheduler disetup
- [ ] Web server dikonfigurasi dengan benar
- [ ] QR Code generation works
- [ ] WhatsApp integration tested
- [ ] Logs dapat diakses dan dimonitor
- [ ] Backup strategy implemented
- [ ] Monitoring tools configured
- [ ] Team ditraining untuk maintenance

---

**Last Updated:** August 12, 2026  
**Version:** 2.0.0
