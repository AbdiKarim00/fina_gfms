# GFMS Development Environment Setup

This guide will help you set up the Kenya Government Fleet Management System development environment.

## Prerequisites

Before you begin, ensure you have the following installed:

- **Docker Desktop** (v20.10+) - [Download](https://www.docker.com/products/docker-desktop)
- **Docker Compose** (v2.0+) - Usually included with Docker Desktop
- **Node.js** (v18+) - For frontend development
- **Flutter** (v3.24+) - For mobile app development
- **Git** - For version control

## Quick Start

### 1. Clone and Navigate

```bash
cd gfms
```

### 2. Run Automated Setup

```bash
./scripts/dev/setup.sh
```

This script will:
- Build Docker containers
- Start PostgreSQL with PostGIS, Redis, and other services
- Install backend dependencies
- Generate Laravel application key
- Run database migrations
- Start all services

### 3. Verify Installation

After setup completes, verify services are running:

```bash
docker-compose ps
```

You should see all services in "Up" state.

## Service URLs

Once setup is complete, access the following services:

| Service | URL | Credentials |
|---------|-----|-------------|
| Backend API | http://localhost:8000 | N/A |
| MailHog (Email Testing) | http://localhost:8025 | N/A |
| pgAdmin (Database) | http://localhost:5050 | admin@gfms.go.ke / admin |
| WebSocket Server | ws://localhost:8080 | N/A |

## Manual Setup (Alternative)

If you prefer manual setup or the script fails:

### 1. Start Infrastructure Services

```bash
docker-compose up -d postgres redis mailhog pgadmin
```

### 2. Wait for PostgreSQL

```bash
# Wait about 10 seconds for PostgreSQL to initialize
sleep 10
```

### 3. Install Backend Dependencies

```bash
docker-compose run --rm app composer install
```

### 4. Generate Application Key

```bash
docker-compose run --rm app php artisan key:generate
```

### 5. Run Migrations

```bash
docker-compose run --rm app php artisan migrate
```

### 6. Start All Services

```bash
docker-compose up -d
```

## Frontend Setup

```bash
cd apps/frontend
npm install
npm run dev
```

Frontend will be available at http://localhost:3000

## Mobile Setup

```bash
cd apps/mobile
flutter pub get
flutter run
```

## Common Commands

### Backend (Laravel)

```bash
# Run artisan commands
docker-compose exec app php artisan [command]

# Run migrations
docker-compose exec app php artisan migrate

# Create migration
docker-compose exec app php artisan make:migration create_table_name

# Create model
docker-compose exec app php artisan make:model ModelName

# Run tests
docker-compose exec app php artisan test

# Clear cache
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Access Laravel Tinker
docker-compose exec app php artisan tinker
```

### Database

```bash
# Access PostgreSQL CLI
docker-compose exec postgres psql -U gfms -d gfms

# Backup database
docker-compose exec postgres pg_dump -U gfms gfms > backup.sql

# Restore database
docker-compose exec -T postgres psql -U gfms gfms < backup.sql

# Reset database (WARNING: Deletes all data)
./scripts/dev/reset.sh
```

### Docker

```bash
# View logs
docker-compose logs -f

# View specific service logs
docker-compose logs -f app
docker-compose logs -f postgres

# Restart services
docker-compose restart

# Stop all services
docker-compose down

# Stop and remove volumes (WARNING: Deletes all data)
docker-compose down -v

# Rebuild containers
docker-compose build --no-cache
docker-compose up -d
```

### Queue Workers

```bash
# View queue worker logs
docker-compose logs -f queue

# Restart queue workers
docker-compose restart queue
```

## Troubleshooting

### Port Already in Use

If you get "port already in use" errors:

```bash
# Check what's using the port
lsof -i :8000  # or :5432, :6379, etc.

# Kill the process or change the port in docker-compose.yml
```

### PostgreSQL Connection Failed

```bash
# Check if PostgreSQL is running
docker-compose ps postgres

# View PostgreSQL logs
docker-compose logs postgres

# Restart PostgreSQL
docker-compose restart postgres
```

### Permission Denied Errors

```bash
# Fix Laravel storage permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
```

### Composer Install Fails

```bash
# Clear composer cache
docker-compose run --rm app composer clear-cache

# Try installing again
docker-compose run --rm app composer install
```

### APP_KEY Not Set

```bash
# Generate new application key
docker-compose exec app php artisan key:generate
```

## Development Workflow

### 1. Start Your Day

```bash
# Start all services
docker-compose up -d

# Check service status
docker-compose ps

# View logs
docker-compose logs -f
```

### 2. Make Changes

- Edit code in your IDE
- Changes to PHP files are reflected immediately (no rebuild needed)
- For frontend, run `npm run dev` for hot reload

### 3. Run Tests

```bash
# Backend tests
docker-compose exec app php artisan test

# Or use the script
./scripts/dev/test.sh
```

### 4. End Your Day

```bash
# Stop services (keeps data)
docker-compose stop

# Or stop and remove containers (keeps data in volumes)
docker-compose down
```

## Environment Variables

### Backend (.env)

Key variables to configure in `apps/backend/.env`:

```env
# Application
APP_KEY=                    # Generated by setup script
APP_ENV=local
APP_DEBUG=true

# Database
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_DATABASE=gfms
DB_USERNAME=gfms
DB_PASSWORD=gfms

# External APIs (configure these)
NTSA_API_URL=
NTSA_API_KEY=
IFMIS_API_URL=
IFMIS_API_KEY=
CMTE_API_URL=
CMTE_API_KEY=
```

### Frontend (.env)

Configure in `apps/frontend/.env`:

```env
VITE_API_BASE_URL=http://localhost:8000/api/v1
VITE_APP_URL=http://localhost:3000
```

### Mobile (.env)

Configure in `apps/mobile/.env`:

```env
API_BASE_URL=http://localhost:8000/api/v1
```

## Next Steps

After setup is complete:

1. ✅ Review the [PROJECT_AUDIT.md](PROJECT_AUDIT.md) for project status
2. 📝 Read the [PRD_SRS_Document.md](../PRD_SRS_Document.md) for requirements
3. 🏗️ Start implementing features following the phased approach
4. 🧪 Write tests as you develop
5. 📚 Document your code and APIs

## Getting Help

- Check the [PROJECT_AUDIT.md](PROJECT_AUDIT.md) for known issues
- Review Docker logs: `docker-compose logs -f`
- Check Laravel logs: `apps/backend/storage/logs/laravel.log`
- Consult the tech stack document for architecture decisions

## Production Deployment

⚠️ **DO NOT use this setup for production!**

For production deployment:
- Use proper SSL/TLS certificates
- Configure environment-specific settings
- Set up proper backup and monitoring
- Follow security best practices
- Use production-grade infrastructure

See `docs/deployment/` (to be created) for production deployment guides.
