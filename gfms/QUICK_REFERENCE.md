# GFMS Quick Reference Card

Quick commands and URLs for daily development.

## 🚀 Essential Commands

### Start/Stop
```bash
make up              # Start all services
make down            # Stop all services
make restart         # Restart services
make logs            # View all logs
```

### Development
```bash
make shell           # Access Laravel container
make tinker          # Laravel Tinker REPL
make test            # Run all tests
make migrate         # Run migrations
make fresh           # Fresh DB with seeds
```

### Maintenance
```bash
make clean           # Clear all caches
make rebuild         # Rebuild containers
make backup          # Backup database
```

## 🌐 Service URLs

| Service | URL | Purpose |
|---------|-----|---------|
| Backend API | http://localhost:8000 | Laravel API |
| Frontend | http://localhost:3000 | React app |
| MailHog | http://localhost:8025 | Email testing |
| pgAdmin | http://localhost:5050 | Database admin |
| WebSocket | ws://localhost:8080 | Real-time |

### pgAdmin Credentials
- Email: `admin@gfms.go.ke`
- Password: `admin`

## 📦 Docker Commands

```bash
# View running containers
docker-compose ps

# View logs for specific service
docker-compose logs -f app
docker-compose logs -f postgres
docker-compose logs -f queue

# Execute command in container
docker-compose exec app [command]

# Restart specific service
docker-compose restart app
docker-compose restart queue

# Remove everything (including volumes)
docker-compose down -v
```

## 🐘 Laravel Artisan

```bash
# Run inside container
docker-compose exec app php artisan [command]

# Common commands
php artisan migrate              # Run migrations
php artisan migrate:fresh        # Fresh migrations
php artisan migrate:rollback     # Rollback last migration
php artisan db:seed              # Run seeders
php artisan make:model Vehicle   # Create model
php artisan make:controller VehicleController --api
php artisan make:migration create_vehicles_table
php artisan route:list           # List all routes
php artisan test                 # Run tests
php artisan tinker               # Interactive shell
php artisan cache:clear          # Clear cache
php artisan config:clear         # Clear config cache
php artisan queue:work           # Process queue jobs
```

## 🗄️ Database

```bash
# Access PostgreSQL CLI
docker-compose exec postgres psql -U gfms -d gfms

# Or use shortcut
make db

# Common SQL commands
\dt                  # List tables
\d table_name        # Describe table
\l                   # List databases
\dn                  # List schemas
\q                   # Quit

# Backup
make backup

# Restore
docker-compose exec -T postgres psql -U gfms gfms < backup.sql
```

## 🧪 Testing

```bash
# Backend (Pest)
make test
docker-compose exec app php artisan test
docker-compose exec app php artisan test --filter=VehicleTest

# Frontend
cd apps/frontend
npm test

# Mobile
cd apps/mobile
flutter test
```

## 📝 Code Generation

### Backend (Laravel)
```bash
# Model with migration
php artisan make:model Vehicle -m

# Controller (API)
php artisan make:controller Api/VehicleController --api

# Request validation
php artisan make:request StoreVehicleRequest

# Resource (API transformation)
php artisan make:resource VehicleResource

# Seeder
php artisan make:seeder VehicleSeeder

# Factory
php artisan make:factory VehicleFactory

# Job (Queue)
php artisan make:job ProcessGpsData

# Event
php artisan make:event VehicleCreated

# Listener
php artisan make:listener SendVehicleNotification
```

### Frontend (React)
```bash
# Component
# Create manually in apps/frontend/src/components/

# Page (Inertia)
# Create manually in apps/frontend/src/pages/
```

### Mobile (Flutter)
```bash
# Generate code (Freezed, JSON)
cd apps/mobile
flutter pub run build_runner build --delete-conflicting-outputs
```

## 🔍 Debugging

### View Logs
```bash
# All services
make logs

# Specific service
docker-compose logs -f app
docker-compose logs -f queue
docker-compose logs -f postgres

# Laravel logs
tail -f apps/backend/storage/logs/laravel.log
```

### Laravel Telescope
```bash
# Access at http://localhost:8000/telescope
# (After installing: composer require laravel/telescope)
```

### Laravel Horizon
```bash
# Access at http://localhost:8000/horizon
# (After installing: composer require laravel/horizon)
```

## 🔧 Environment Variables

### Backend (.env)
```env
APP_KEY=                    # Auto-generated
DB_HOST=postgres
DB_DATABASE=gfms
DB_USERNAME=gfms
DB_PASSWORD=gfms
REDIS_HOST=redis
```

### Frontend (.env)
```env
VITE_API_BASE_URL=http://localhost:8000/api/v1
```

### Mobile (.env)
```env
API_BASE_URL=http://localhost:8000/api/v1
```

## 🚨 Common Issues

### Port Already in Use
```bash
# Find process using port
lsof -i :8000

# Kill process
kill -9 [PID]
```

### Database Connection Failed
```bash
# Restart PostgreSQL
docker-compose restart postgres

# Check if running
docker-compose ps postgres
```

### Permission Denied
```bash
# Fix Laravel permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Composer Install Fails
```bash
# Clear cache
docker-compose run --rm app composer clear-cache

# Reinstall
docker-compose run --rm app composer install
```

### APP_KEY Not Set
```bash
make key
# or
docker-compose exec app php artisan key:generate
```

## 📊 Performance

### Clear All Caches
```bash
make clean
```

### Optimize for Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

## 🔐 Security

### Generate Keys
```bash
# Application key
php artisan key:generate

# JWT secret (if using)
php artisan jwt:secret

# Passport keys (if using)
php artisan passport:keys
```

## 📚 Useful Links

- [Laravel Docs](https://laravel.com/docs/11.x)
- [Inertia.js Docs](https://inertiajs.com/)
- [Flutter Docs](https://docs.flutter.dev/)
- [PostgreSQL Docs](https://www.postgresql.org/docs/)
- [PostGIS Docs](https://postgis.net/documentation/)

## 💡 Tips

1. **Use Make commands** - They're shortcuts for common tasks
2. **Check logs first** - Most issues show up in logs
3. **Keep containers running** - Faster than starting/stopping
4. **Use Tinker** - Great for testing code snippets
5. **Write tests** - They save time in the long run

## 🆘 Getting Help

1. Check [SETUP.md](SETUP.md) for detailed setup
2. Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) for status
3. Check logs: `make logs`
4. Search Laravel/Flutter docs
5. Contact the team

---

**Keep this handy for daily development!** 📌
