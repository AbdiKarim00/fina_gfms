# MacPorts Setup Guide

## Overview
Since you're using MacPorts on macOS 12, this guide provides a MacPorts-specific setup that avoids Homebrew conflicts.

---

## Prerequisites

✅ **MacPorts** - Already installed  
✅ **PHP 8.3** - Already installed and active  
✅ **Node.js** - Already installed  

---

## Quick Setup

### One-Command Installation
```bash
cd gfms
./setup-macports.sh
```

This will:
- Install required PHP extensions via MacPorts
- Install PostgreSQL 15 and Redis
- Configure PHP for Laravel
- Set up database and user
- Install all dependencies
- Create startup scripts

**Time:** ~15-20 minutes

---

## Manual Installation (if script fails)

### Step 1: Install PHP Extensions
```bash
# Required extensions for Laravel
sudo port install php83-mbstring
sudo port install php83-iconv
sudo port install php83-pdo
sudo port install php83-pdo_sqlite
sudo port install php83-pdo_pgsql
sudo port install php83-redis
sudo port install php83-curl
sudo port install php83-openssl
sudo port install php83-zip
sudo port install php83-gd
sudo port install php83-intl
sudo port install php83-xml
sudo port install php83-dom
sudo port install php83-fileinfo
sudo port install php83-tokenizer
sudo port install php83-ctype
sudo port install php83-json
sudo port install php83-bcmath
```

### Step 2: Install Database and Cache
```bash
# Install PostgreSQL
sudo port install postgresql15 +universal
sudo port install postgresql15-server +universal

# Install Redis
sudo port install redis +universal
```

### Step 3: Install Composer
```bash
# Download and install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /opt/local/bin/composer
sudo chmod +x /opt/local/bin/composer
```

### Step 4: Configure PHP
```bash
# Copy PHP configuration
sudo cp /opt/local/etc/php83/php.ini-development /opt/local/etc/php83/php.ini

# Add Laravel-specific settings
sudo tee -a /opt/local/etc/php83/php.ini > /dev/null << 'EOF'

; Laravel optimizations
memory_limit = 512M
max_execution_time = 300
upload_max_filesize = 50M
post_max_size = 50M

; Enable extensions
extension=mbstring
extension=pdo
extension=pdo_pgsql
extension=redis
extension=curl
extension=zip
extension=gd
extension=intl
EOF
```

### Step 5: Initialize Services
```bash
# Initialize PostgreSQL
sudo mkdir -p /opt/local/var/db/postgresql15
sudo chown _postgresql:_postgresql /opt/local/var/db/postgresql15
sudo -u _postgresql /opt/local/lib/postgresql15/bin/initdb -D /opt/local/var/db/postgresql15/defaultdb

# Start services
sudo port load postgresql15-server
sudo port load redis

# Create database
sudo -u _postgresql /opt/local/lib/postgresql15/bin/createdb gfms
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "CREATE USER gfms WITH PASSWORD 'gfms';"
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "GRANT ALL PRIVILEGES ON DATABASE gfms TO gfms;"
```

### Step 6: Setup Application
```bash
cd gfms/apps/backend

# Copy environment file
cp .env.example .env

# Edit .env for MacPorts setup
# Update these lines:
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=gfms
# DB_USERNAME=gfms
# DB_PASSWORD=gfms

# Install dependencies
/opt/local/bin/composer install

# Setup Laravel
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

# Setup frontend
cd ../frontend
npm install
cp .env.example .env
```

---

## Daily Workflow

### Start Services
```bash
cd gfms
./start-macports.sh
```

### Run Development Servers (4 terminals)

**Terminal 1 - Backend:**
```bash
cd gfms/apps/backend
php artisan serve
```

**Terminal 2 - Frontend:**
```bash
cd gfms/apps/frontend
npm run dev
```

**Terminal 3 - Queue Worker:**
```bash
cd gfms/apps/backend
php artisan queue:work
```

**Terminal 4 - WebSocket (optional):**
```bash
cd gfms/apps/backend
php artisan reverb:start
```

---

## MacPorts Service Management

### Check Service Status
```bash
# List all loaded services
sudo port load

# Check specific services
port installed | grep postgresql
port installed | grep redis
```

### Start Services
```bash
sudo port load postgresql15-server
sudo port load redis
```

### Stop Services
```bash
sudo port unload postgresql15-server
sudo port unload redis
```

### Restart Services
```bash
sudo port unload postgresql15-server
sudo port load postgresql15-server
```

---

## Troubleshooting

### PHP Extensions Not Loading
```bash
# Check loaded extensions
php -m

# Check PHP configuration
php --ini

# Verify extension files exist
ls /opt/local/lib/php83/extensions/no-debug-non-zts-20230831/
```

### Composer Issues
```bash
# Use full path to composer
/opt/local/bin/composer --version

# Check PHP extensions for Composer
php -m | grep -E "(mbstring|iconv|openssl)"
```

### Database Connection Issues
```bash
# Check if PostgreSQL is running
sudo port load | grep postgresql

# Test connection manually
/opt/local/lib/postgresql15/bin/psql -h 127.0.0.1 -U gfms -d gfms

# Check PostgreSQL logs
tail -f /opt/local/var/db/postgresql15/defaultdb/log/postgresql-*.log
```

### Redis Connection Issues
```bash
# Check if Redis is running
sudo port load | grep redis

# Test Redis connection
/opt/local/bin/redis-cli ping
```

### Port Conflicts
```bash
# Check what's using port 5432 (PostgreSQL)
lsof -i :5432

# Check what's using port 6379 (Redis)
lsof -i :6379

# Check what's using port 8000 (Laravel)
lsof -i :8000
```

---

## File Locations (MacPorts)

### PHP
- **Binary:** `/opt/local/bin/php`
- **Config:** `/opt/local/etc/php83/php.ini`
- **Extensions:** `/opt/local/lib/php83/extensions/`

### PostgreSQL
- **Binary:** `/opt/local/lib/postgresql15/bin/`
- **Data:** `/opt/local/var/db/postgresql15/defaultdb/`
- **Config:** `/opt/local/var/db/postgresql15/defaultdb/postgresql.conf`

### Redis
- **Binary:** `/opt/local/bin/redis-server`
- **Config:** `/opt/local/etc/redis.conf`

### Composer
- **Binary:** `/opt/local/bin/composer`

---

## Performance Tips

### PHP Optimization
```bash
# Edit PHP configuration
sudo nano /opt/local/etc/php83/php.ini

# Add these optimizations:
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
```

### PostgreSQL Optimization
```bash
# Edit PostgreSQL configuration
sudo nano /opt/local/var/db/postgresql15/defaultdb/postgresql.conf

# Add these settings:
shared_buffers = 256MB
effective_cache_size = 1GB
work_mem = 4MB
```

---

## Advantages of MacPorts Setup

✅ **Native macOS 12 support** - Designed for older macOS versions  
✅ **No Homebrew conflicts** - Uses separate /opt/local directory  
✅ **Stable packages** - Well-tested on macOS 12  
✅ **Better resource usage** - No Docker overhead  
✅ **Easier debugging** - Native tools and processes  

---

## Access URLs

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000/api/v1
- **Database:** localhost:5432 (gfms/gfms)
- **Redis:** localhost:6379

---

## Test Credentials

**Super Admin:**
- Email: `admin@gfms.go.ke`
- Password: `password`

**Transport Officer:**
- Email: `officer@gfms.go.ke`
- Password: `password`

---

## Next Steps

1. **Run the setup script:** `./setup-macports.sh`
2. **Start services:** `./start-macports.sh`
3. **Run development servers** in 4 terminals
4. **Access the application** at http://localhost:3000
5. **Test the bookings module** we completed

This MacPorts setup should work perfectly with your macOS 12 system and avoid all the Homebrew conflicts!