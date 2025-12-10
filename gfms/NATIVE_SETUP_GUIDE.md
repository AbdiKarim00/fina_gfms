# Native Setup Guide (Docker Alternative)

## Overview
This guide helps you run the GFMS system natively on macOS without Docker to avoid overheating issues.

---

## Prerequisites

✅ **Homebrew** - Already installed  
✅ **Node.js** - Already installed  
❌ **PHP** - Need to install  
❌ **PostgreSQL** - Need to install  
❌ **Redis** - Need to install  
❌ **Composer** - Need to install  

---

## Step 1: Install Required Services

### Install PHP 8.2+ with Extensions
```bash
# Install PHP with required extensions
brew install php@8.2

# Install additional PHP extensions
brew install php@8.2-redis php@8.2-pgsql

# Add PHP to PATH
echo 'export PATH="/usr/local/opt/php@8.2/bin:$PATH"' >> ~/.zshrc
echo 'export PATH="/usr/local/opt/php@8.2/sbin:$PATH"' >> ~/.zshrc
source ~/.zshrc

# Verify PHP installation
php --version
```

### Install Composer
```bash
# Install Composer (PHP package manager)
brew install composer

# Verify installation
composer --version
```

### Install PostgreSQL
```bash
# Install PostgreSQL
brew install postgresql@15

# Start PostgreSQL service
brew services start postgresql@15

# Add PostgreSQL to PATH
echo 'export PATH="/usr/local/opt/postgresql@15/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc

# Create database and user
createdb gfms
psql gfms -c "CREATE USER gfms WITH PASSWORD 'gfms';"
psql gfms -c "GRANT ALL PRIVILEGES ON DATABASE gfms TO gfms;"
psql gfms -c "ALTER USER gfms CREATEDB;"
```

### Install Redis
```bash
# Install Redis
brew install redis

# Start Redis service
brew services start redis

# Verify Redis is running
redis-cli ping
# Should return: PONG
```

### Install PostGIS (for geospatial features)
```bash
# Install PostGIS extension
brew install postgis

# Enable PostGIS in your database
psql gfms -c "CREATE EXTENSION IF NOT EXISTS postgis;"
```

---

## Step 2: Configure Environment

### Backend Configuration
```bash
cd gfms/apps/backend

# Copy environment file
cp .env.example .env

# Edit .env file with native settings
```

**Update `.env` file:**
```env
APP_NAME="Kenya GFMS"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# Native Database Configuration
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=gfms
DB_USERNAME=gfms
DB_PASSWORD=gfms

# Native Redis Configuration
BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
FILESYSTEM_DISK=local
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration (using local mail)
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@gfms.go.ke"
MAIL_FROM_NAME="${APP_NAME}"

# SMS Configuration
SMS_PROVIDER=africastalking
AFRICASTALKING_USERNAME=sandbox
AFRICASTALKING_API_KEY=your-api-key
AFRICASTALKING_FROM=GFMS

# WebSocket Configuration
REVERB_APP_ID=gfms
REVERB_APP_KEY=your-reverb-key
REVERB_APP_SECRET=your-reverb-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### Frontend Configuration
```bash
cd gfms/apps/frontend

# Copy environment file
cp .env.example .env

# Edit .env file
```

**Update frontend `.env` file:**
```env
VITE_APP_NAME="Kenya GFMS"
VITE_API_BASE_URL=http://localhost:8000/api/v1
VITE_APP_URL=http://localhost:3000

# WebSocket Configuration
VITE_REVERB_APP_KEY=your-reverb-key
VITE_REVERB_HOST=localhost
VITE_REVERB_PORT=8080
VITE_REVERB_SCHEME=http
```

---

## Step 3: Install Dependencies

### Backend Dependencies
```bash
cd gfms/apps/backend

# Install PHP dependencies
composer install

# Generate application key
php artisan key:generate

# Run database migrations
php artisan migrate

# Seed the database
php artisan db:seed

# Create storage link
php artisan storage:link

# Cache configuration
php artisan config:cache
```

### Frontend Dependencies
```bash
cd gfms/apps/frontend

# Install Node.js dependencies
npm install

# Build for development
npm run dev
```

---

## Step 4: Start Services

You'll need **4 terminal windows/tabs**:

### Terminal 1: Backend Server
```bash
cd gfms/apps/backend
php artisan serve --host=0.0.0.0 --port=8000
```

### Terminal 2: Frontend Server
```bash
cd gfms/apps/frontend
npm run dev
```

### Terminal 3: Queue Worker
```bash
cd gfms/apps/backend
php artisan queue:work --tries=3 --timeout=90
```

### Terminal 4: WebSocket Server (Optional)
```bash
cd gfms/apps/backend
php artisan reverb:start --host=0.0.0.0 --port=8080
```

---

## Step 5: Verify Installation

### Check Services
```bash
# Check if PostgreSQL is running
brew services list | grep postgresql

# Check if Redis is running
brew services list | grep redis

# Test database connection
php artisan tinker
# In tinker: DB::connection()->getPdo();
# Should not throw errors
```

### Access Applications
- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000/api/v1
- **WebSocket:** ws://localhost:8080

### Test Login
Use these test credentials:
```
Super Admin:
Email: admin@gfms.go.ke
Password: password

Transport Officer:
Email: officer@gfms.go.ke
Password: password
```

---

## Step 6: Database Management

### Using psql (Command Line)
```bash
# Connect to database
psql gfms -U gfms

# Common commands
\dt          # List tables
\d bookings  # Describe bookings table
\q           # Quit
```

### Using pgAdmin (GUI Alternative)
```bash
# Install pgAdmin
brew install --cask pgadmin4

# Launch pgAdmin
open -a pgAdmin\ 4

# Connection details:
# Host: localhost
# Port: 5432
# Database: gfms
# Username: gfms
# Password: gfms
```

---

## Step 7: Performance Optimization

### PHP Configuration
Create/edit `/usr/local/etc/php/8.2/conf.d/99-custom.ini`:
```ini
; Memory and execution limits
memory_limit = 512M
max_execution_time = 300
max_input_time = 300

; File upload limits
upload_max_filesize = 50M
post_max_size = 50M

; OPcache for better performance
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0

; Redis session configuration
session.save_handler = redis
session.save_path = "tcp://127.0.0.1:6379"
```

### PostgreSQL Optimization
Edit `/usr/local/var/postgresql@15/postgresql.conf`:
```conf
# Memory settings
shared_buffers = 256MB
effective_cache_size = 1GB
work_mem = 4MB
maintenance_work_mem = 64MB

# Connection settings
max_connections = 100

# Performance settings
random_page_cost = 1.1
effective_io_concurrency = 200
```

Restart PostgreSQL:
```bash
brew services restart postgresql@15
```

---

## Step 8: Development Workflow

### Daily Startup (4 commands in separate terminals)
```bash
# Terminal 1: Backend
cd gfms/apps/backend && php artisan serve

# Terminal 2: Frontend  
cd gfms/apps/frontend && npm run dev

# Terminal 3: Queue Worker
cd gfms/apps/backend && php artisan queue:work

# Terminal 4: WebSocket (if needed)
cd gfms/apps/backend && php artisan reverb:start
```

### Create Startup Script
Create `gfms/start-native.sh`:
```bash
#!/bin/bash

echo "🚀 Starting GFMS Native Setup..."

# Check if services are running
if ! brew services list | grep -q "postgresql.*started"; then
    echo "📦 Starting PostgreSQL..."
    brew services start postgresql@15
fi

if ! brew services list | grep -q "redis.*started"; then
    echo "📦 Starting Redis..."
    brew services start redis
fi

echo "✅ Services started!"
echo ""
echo "Now run these commands in separate terminals:"
echo "1. cd gfms/apps/backend && php artisan serve"
echo "2. cd gfms/apps/frontend && npm run dev"
echo "3. cd gfms/apps/backend && php artisan queue:work"
echo "4. cd gfms/apps/backend && php artisan reverb:start"
```

Make it executable:
```bash
chmod +x gfms/start-native.sh
```

---

## Step 9: Troubleshooting

### Common Issues

**Issue:** PHP not found  
**Solution:**
```bash
echo 'export PATH="/usr/local/opt/php@8.2/bin:$PATH"' >> ~/.zshrc
source ~/.zshrc
```

**Issue:** PostgreSQL connection refused  
**Solution:**
```bash
brew services restart postgresql@15
# Check if running: brew services list | grep postgresql
```

**Issue:** Redis connection refused  
**Solution:**
```bash
brew services restart redis
# Test: redis-cli ping
```

**Issue:** Permission denied on database  
**Solution:**
```bash
psql gfms -c "GRANT ALL PRIVILEGES ON DATABASE gfms TO gfms;"
psql gfms -c "GRANT ALL ON SCHEMA public TO gfms;"
```

**Issue:** Frontend can't connect to backend  
**Solution:** Check that backend is running on port 8000 and frontend .env has correct API URL

---

## Step 10: Stopping Services

### Stop Development Servers
- Press `Ctrl+C` in each terminal running the servers

### Stop Background Services
```bash
# Stop PostgreSQL
brew services stop postgresql@15

# Stop Redis  
brew services stop redis
```

### Complete Shutdown Script
Create `gfms/stop-native.sh`:
```bash
#!/bin/bash

echo "🛑 Stopping GFMS Native Setup..."

# Stop background services
brew services stop postgresql@15
brew services stop redis

echo "✅ All services stopped!"
```

---

## Benefits of Native Setup

✅ **No Docker overhead** - Reduced CPU/memory usage  
✅ **Faster startup** - No container initialization  
✅ **Better performance** - Direct system access  
✅ **Easier debugging** - Native tools and IDEs work better  
✅ **Lower resource usage** - No virtualization layer  
✅ **Persistent data** - No volume management needed  

---

## Resource Usage Comparison

### Docker Setup
- **Memory:** ~2-4GB (containers + overhead)
- **CPU:** High (virtualization + multiple containers)
- **Disk:** ~5-10GB (images + volumes)
- **Startup:** 30-60 seconds

### Native Setup  
- **Memory:** ~500MB-1GB (just the applications)
- **CPU:** Low (native processes)
- **Disk:** ~1-2GB (just dependencies)
- **Startup:** 5-10 seconds

---

## Next Steps

1. **Run the installation commands** above
2. **Test the system** with the bookings module
3. **Create the startup scripts** for easier daily use
4. **Monitor resource usage** to confirm improvement

The native setup should significantly reduce your system load while maintaining full functionality!