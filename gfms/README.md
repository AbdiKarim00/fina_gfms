# Kenya Government Fleet Management System (GFMS)

A comprehensive national fleet management platform for the Kenyan government to digitize, manage, monitor, and enforce compliance across all government vehicles in Ministries, Departments, Agencies, and County Governments.

## 🎯 Project Overview

**Target Scale:** 20,000+ vehicles across 47 counties + all ministries  
**Status:** Early Development Phase (~10% complete)  
**Tech Stack:** Laravel 11 | React + Inertia.js | Flutter 3.24 | PostgreSQL + PostGIS

### Core Features
- 🚗 National Fleet Registry
- 📍 Real-time GPS Tracking with Geo-fencing
- 📝 Digital Work Tickets & GP55 Motor Logbook
- ⛽ Fuel Management & Anomaly Detection
- 🔧 Maintenance Scheduling & Tracking
- 👤 Driver Management & License Validation
- 📊 National Reporting & Analytics
- 🔗 Integration with NTSA, IFMIS, CMTE

## 🚀 Quick Start

### Prerequisites
- Docker Desktop (v20.10+)
- Node.js (v18+) - for frontend
- Flutter (v3.24+) - for mobile
- Git

### One-Command Setup

```bash
cd gfms
./scripts/dev/setup.sh
```

This will:
- ✅ Build Docker containers
- ✅ Start PostgreSQL with PostGIS, Redis, and services
- ✅ Install backend dependencies
- ✅ Generate application keys
- ✅ Run database migrations
- ✅ Start all services

### Access Services

| Service | URL | Credentials |
|---------|-----|-------------|
| 🌐 Backend API | http://localhost:8000 | - |
| 📧 MailHog | http://localhost:8025 | - |
| 🐘 pgAdmin | http://localhost:5050 | admin@gfms.go.ke / admin |
| 🔌 WebSocket | ws://localhost:8080 | - |

## 📁 Project Structure

```
gfms/
├── apps/
│   ├── backend/        # Laravel 11 API (PHP 8.3)
│   ├── frontend/       # React + Inertia.js + TypeScript
│   └── mobile/         # Flutter 3.24 (Offline-first)
├── packages/
│   ├── core/           # Shared business logic
│   ├── ui/             # Shared UI components
│   └── types/          # TypeScript type definitions
├── infrastructure/
│   ├── docker/         # Docker configurations
│   └── terraform/      # Infrastructure as Code
├── docs/               # Documentation
└── scripts/            # Development scripts
```

## 🛠️ Common Commands

### Using Make (Recommended)

```bash
make help           # Show all available commands
make up             # Start all services
make down           # Stop all services
make logs           # View logs
make shell          # Access Laravel container
make test           # Run tests
make migrate        # Run migrations
make fresh          # Fresh database with seeds
```

### Using Docker Compose

```bash
docker-compose up -d                          # Start services
docker-compose down                           # Stop services
docker-compose logs -f                        # View logs
docker-compose exec app php artisan [cmd]    # Run artisan
docker-compose exec app php artisan test     # Run tests
```

### Backend (Laravel)

```bash
# Inside container
docker-compose exec app php artisan migrate
docker-compose exec app php artisan make:model Vehicle
docker-compose exec app php artisan test
docker-compose exec app php artisan tinker
```

### Frontend

```bash
cd apps/frontend
npm install
npm run dev         # Start dev server at http://localhost:3000
npm run build       # Build for production
```

### Mobile

```bash
cd apps/mobile
flutter pub get
flutter run
flutter test
```

## 📚 Documentation

- **[SETUP.md](SETUP.md)** - Detailed setup instructions
- **[PROJECT_AUDIT.md](PROJECT_AUDIT.md)** - Current project status & roadmap
- **[PRD_SRS_Document.md](../PRD_SRS_Document.md)** - Requirements & specifications
- **[Final Tech Stack.md](../Final%20Tech%20Stack%20-%20Kenya%20Government%20Fleet%20Management%20System.md)** - Technology decisions

## 🔧 Development Workflow

1. **Start your day**
   ```bash
   make up
   ```

2. **Make changes** - Edit code in your IDE

3. **Run tests**
   ```bash
   make test
   ```

4. **Check logs**
   ```bash
   make logs
   ```

5. **End your day**
   ```bash
   make down
   ```

## 🧪 Testing

```bash
# Backend tests (Pest)
make test
# or
docker-compose exec app php artisan test

# Frontend tests
cd apps/frontend && npm test

# Mobile tests
cd apps/mobile && flutter test
```

## 🐛 Troubleshooting

### Port Already in Use
```bash
# Check what's using the port
lsof -i :8000

# Change port in docker-compose.yml if needed
```

### Database Connection Failed
```bash
# Restart PostgreSQL
docker-compose restart postgres

# Check logs
docker-compose logs postgres
```

### Permission Errors
```bash
# Fix Laravel permissions
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

See [SETUP.md](SETUP.md) for more troubleshooting tips.

## 📊 Project Status

**Overall Completion: ~10%**

| Component | Status | Completion |
|-----------|--------|------------|
| Documentation | ✅ Excellent | 70% |
| Backend | ⚠️ Basic setup | 15% |
| Frontend | ⚠️ Config only | 10% |
| Mobile | ⚠️ Config only | 8% |
| Infrastructure | ⚠️ Docker basic | 5% |
| Testing | ❌ Not started | 0% |
| Integrations | ❌ Not started | 0% |

See [PROJECT_AUDIT.md](PROJECT_AUDIT.md) for detailed status.

## 🗺️ Development Roadmap

### Phase 1: Foundation (Weeks 2-4)
- ✅ Docker environment setup
- 🔄 Backend: Models, controllers, auth, RBAC
- 🔄 Frontend: Inertia setup, auth pages, dashboard
- 🔄 Mobile: Auth flow, offline DB, API client

### Phase 2: Core Features (Weeks 5-8)
- GPS tracking ingestion and display
- Work ticket system
- GP55 digital logbook
- Driver/vehicle management

### Phase 3: Advanced Features (Weeks 9-12)
- Maintenance scheduling
- Fuel management
- Reporting engine
- Geo-fencing

### Phase 4: Integrations (Weeks 13-16)
- NTSA integration
- IFMIS integration
- CMTE integration
- Fuel card providers

## 🤝 Contributing

This is a government project. For contribution guidelines, please contact the project maintainers.

## 📄 License

This project is proprietary software owned by the Government of Kenya.

## 🆘 Support

For issues or questions:
1. Check [SETUP.md](SETUP.md) for setup issues
2. Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) for known issues
3. Check Docker logs: `make logs`
4. Contact the development team

---

**Built for the Government of Kenya** 🇰🇪
