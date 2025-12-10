# Native Setup - Quick Start

## 🚀 One-Command Setup

```bash
cd gfms
./setup-native.sh
```

This will:
- Install PHP 8.2, PostgreSQL 15, Redis, Composer
- Create and configure the database
- Install all dependencies
- Set up environment files

**Time:** ~10-15 minutes

---

## 📋 Daily Workflow

### Start Services (Once per day)
```bash
cd gfms
./start-native.sh
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

## 🌐 Access URLs

- **Frontend:** http://localhost:3000
- **Backend API:** http://localhost:8000/api/v1

---

## 🔑 Test Credentials

**Super Admin:**
- Email: `admin@gfms.go.ke`
- Password: `password`

**Transport Officer:**
- Email: `officer@gfms.go.ke`
- Password: `password`

---

## 🛑 Stop Services

### Stop Development Servers
Press `Ctrl+C` in each terminal

### Stop Background Services
```bash
cd gfms
./stop-native.sh
```

---

## 🔧 Common Commands

### Database
```bash
# Connect to database
psql gfms -U gfms

# Run migrations
cd apps/backend
php artisan migrate

# Seed database
php artisan db:seed

# Reset database
php artisan migrate:fresh --seed
```

### Cache
```bash
cd apps/backend

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Queue
```bash
cd apps/backend

# Process queue jobs
php artisan queue:work

# Clear failed jobs
php artisan queue:flush
```

---

## 🐛 Troubleshooting

### Services Not Running
```bash
# Check service status
brew services list

# Restart PostgreSQL
brew services restart postgresql@15

# Restart Redis
brew services restart redis
```

### Database Connection Error
```bash
# Check if PostgreSQL is running
brew services list | grep postgresql

# Test connection
psql gfms -U gfms -c "SELECT 1;"
```

### Redis Connection Error
```bash
# Check if Redis is running
brew services list | grep redis

# Test connection
redis-cli ping
# Should return: PONG
```

### Port Already in Use
```bash
# Find process using port 8000
lsof -ti:8000

# Kill process
kill -9 $(lsof -ti:8000)
```

---

## 💡 Tips

1. **Use iTerm2 or Terminal tabs** for multiple terminals
2. **Create aliases** in `~/.zshrc`:
   ```bash
   alias gfms-backend="cd ~/path/to/gfms/apps/backend && php artisan serve"
   alias gfms-frontend="cd ~/path/to/gfms/apps/frontend && npm run dev"
   alias gfms-queue="cd ~/path/to/gfms/apps/backend && php artisan queue:work"
   ```
3. **Monitor logs** in real-time:
   ```bash
   tail -f apps/backend/storage/logs/laravel.log
   ```

---

## 📊 Resource Usage

**Native Setup:**
- Memory: ~500MB-1GB
- CPU: Low (5-15%)
- Startup: 5-10 seconds

**vs Docker:**
- Memory: ~2-4GB
- CPU: High (30-60%)
- Startup: 30-60 seconds

**Result:** ~75% less resource usage! 🎉

---

## 📚 Full Documentation

See `NATIVE_SETUP_GUIDE.md` for detailed instructions and troubleshooting.
