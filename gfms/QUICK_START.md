# GFMS Quick Start Guide 🚀

## You Are Here: Docker Implementation Complete ✅

All infrastructure files are ready. Time to run your environment!

## Step 1: Run Setup (5 minutes)

```bash
cd gfms
chmod +x scripts/dev/*.sh
./scripts/dev/setup.sh
```

**What this does:**
- Builds Docker containers (PHP 8.3, Nginx, PostgreSQL with PostGIS)
- Starts all services
- Installs Laravel dependencies
- Generates application key
- Runs migrations
- Seeds database (optional)

## Step 2: Verify Everything Works

```bash
# Check all services are running
docker-compose ps

# Should show 8 services running:
# - gfms_postgres
# - gfms_redis
# - gfms_app
# - gfms_nginx
# - gfms_queue
# - gfms_scheduler
# - gfms_reverb
# - gfms_pgadmin
# - gfms_mailhog

# Test the API
curl http://localhost:8000/api/health
```

## Step 3: Access Your Services

| Service | URL | Purpose |
|---------|-----|---------|
| Backend API | http://localhost:8000 | Laravel API |
| pgAdmin | http://localhost:5050 | Database management |
| MailHog | http://localhost:8025 | Email testing |
| WebSocket | ws://localhost:8080 | Real-time updates |

**pgAdmin Login:**
- Email: admin@gfms.go.ke
- Password: admin

## Common Commands

```bash
# Start services
make up

# Stop services
make down

# View logs
make logs

# Access Laravel shell
make shell

# Run migrations
make migrate

# Run tests
make test

# See all commands
make help
```

## What's Next? Create Database Schema

Your environment is running, but you have no database tables yet (except cache tables).

**Next step:** Create migrations for all 7 schemas

Run this to see what needs to be created:
```bash
cat INITIALIZATION_AUDIT_AND_BLUEPRINT.md | grep -A 50 "Priority 3: Create Core Database Schema"
```

## Troubleshooting

**Port already in use?**
```bash
# Check what's using port 8000
lsof -i :8000

# Or change port in docker-compose.yml
```

**Database connection failed?**
```bash
# Restart PostgreSQL
docker-compose restart postgres

# Check logs
docker-compose logs postgres
```

**Permission errors?**
```bash
# Fix Laravel permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

**Need to rebuild?**
```bash
make rebuild
```

## Development Workflow

**Morning:**
```bash
make up
make logs  # Check for errors
```

**During development:**
```bash
make shell  # Access container
php artisan make:model Vehicle
php artisan make:controller VehicleController
```

**Before committing:**
```bash
make test   # Run tests
make lint   # Check code style
```

**End of day:**
```bash
make down
```

## Your Progress

- ✅ Documentation complete
- ✅ Docker files created
- ✅ Dependencies installed
- ⏳ **YOU ARE HERE** → Run setup script
- ⬜ Create database schema
- ⬜ Build models & controllers
- ⬜ Create API endpoints
- ⬜ Build frontend pages
- ⬜ Develop mobile app

---

**Ready?** Run: `./scripts/dev/setup.sh`

After setup completes, check `INITIALIZATION_AUDIT_AND_BLUEPRINT.md` for your Week 2 roadmap.
