# 🚀 START HERE - GFMS Quick Start Guide

Welcome to the Kenya Government Fleet Management System (GFMS) project!

## 📋 What You Need to Know

This is a **national-scale fleet management platform** for managing 20,000+ government vehicles across Kenya. The project is currently at **~10% completion** with infrastructure and configuration ready, but application code needs to be built.

## ⚡ Get Started in 5 Minutes

### 1. Prerequisites Check

Make sure you have:
- ✅ Docker Desktop installed and running
- ✅ Git installed
- ✅ Terminal/Command line access

### 2. Run Setup

```bash
cd gfms
./scripts/dev/setup.sh
```

This single command will:
- Build all Docker containers
- Start PostgreSQL, Redis, and all services
- Install backend dependencies
- Generate application keys
- Run database migrations
- Display service URLs

### 3. Verify It Works

```bash
# Check all services are running
docker-compose ps

# You should see all services in "Up" state
```

### 4. Access Services

Open these URLs in your browser:
- **Backend API:** http://localhost:8000
- **Email Testing:** http://localhost:8025
- **Database Admin:** http://localhost:5050 (admin@gfms.go.ke / admin)

## 📚 Essential Documentation

Read these in order:

1. **[README.md](README.md)** - Project overview and quick commands
2. **[SETUP.md](SETUP.md)** - Detailed setup instructions
3. **[QUICK_REFERENCE.md](QUICK_REFERENCE.md)** - Daily development commands
4. **[PROJECT_AUDIT.md](PROJECT_AUDIT.md)** - Current status and roadmap
5. **[ARCHITECTURE.md](ARCHITECTURE.md)** - System architecture diagrams

## 🛠️ Daily Development Commands

```bash
# Start services
make up

# View logs
make logs

# Access Laravel container
make shell

# Run tests
make test

# Run migrations
make migrate

# Stop services
make down
```

See [QUICK_REFERENCE.md](QUICK_REFERENCE.md) for complete command list.

## 📊 Project Status

**Current Phase:** Week 1 Complete ✅  
**Next Phase:** Foundation Development (Weeks 2-4)

### What's Done ✅
- Docker environment fully configured
- PostgreSQL with PostGIS ready
- All required services running
- Development scripts created
- Comprehensive documentation

### What's Next 🔄
- Database migrations for all modules
- Laravel models and controllers
- Authentication system
- API endpoints
- Frontend pages
- Mobile app features

## 🎯 Your First Tasks

### If You're a Backend Developer:
1. Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) Section 3 (Backend)
2. Create database migrations for your assigned module
3. Create Eloquent models
4. Write API controllers
5. Add tests

### If You're a Frontend Developer:
1. Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) Section 4 (Frontend)
2. Set up Inertia.js pages
3. Create React components
4. Implement state management
5. Add form validation

### If You're a Mobile Developer:
1. Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) Section 5 (Mobile)
2. Create main.dart entry point
3. Set up Riverpod providers
4. Implement offline database
5. Create API client

## 🆘 Need Help?

### Common Issues

**Port already in use?**
```bash
lsof -i :8000  # Find what's using the port
```

**Database connection failed?**
```bash
docker-compose restart postgres
docker-compose logs postgres
```

**Permission errors?**
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

See [SETUP.md](SETUP.md) for more troubleshooting.

### Getting Support

1. Check the documentation files listed above
2. Review Docker logs: `make logs`
3. Check Laravel logs: `apps/backend/storage/logs/laravel.log`
4. Contact the team lead

## 📖 Understanding the Project

### Tech Stack
- **Backend:** Laravel 11 (PHP 8.3)
- **Frontend:** React + Inertia.js + TypeScript
- **Mobile:** Flutter 3.24
- **Database:** PostgreSQL 16 + PostGIS
- **Cache:** Redis 7.2
- **WebSocket:** Laravel Reverb

### Key Features to Build
- 🚗 Fleet registry and management
- 📍 Real-time GPS tracking
- 📝 Digital work tickets
- 📖 GP55 motor logbook
- ⛽ Fuel management
- 🔧 Maintenance tracking
- 👤 Driver management
- 📊 Reporting and analytics
- 🔗 NTSA/IFMIS/CMTE integration

### Architecture
See [ARCHITECTURE.md](ARCHITECTURE.md) for detailed diagrams.

## 🎓 Learning Resources

### Laravel
- [Laravel 11 Docs](https://laravel.com/docs/11.x)
- [Laravel Sanctum](https://laravel.com/docs/11.x/sanctum)
- [Spatie Permissions](https://spatie.be/docs/laravel-permission)

### React + Inertia
- [Inertia.js Docs](https://inertiajs.com/)
- [React Docs](https://react.dev/)
- [Tailwind CSS](https://tailwindcss.com/)

### Flutter
- [Flutter Docs](https://docs.flutter.dev/)
- [Riverpod](https://riverpod.dev/)
- [Drift Database](https://drift.simonbinder.eu/)

### PostGIS
- [PostGIS Docs](https://postgis.net/documentation/)
- [Laravel Spatial](https://github.com/MatanYadaev/laravel-eloquent-spatial)

## 🔐 Security Notes

- Never commit `.env` files
- Keep API keys secure
- Use environment variables for secrets
- Follow Laravel security best practices
- Implement proper RBAC

## 📝 Code Standards

### Backend (PHP)
```bash
# Check code style
make lint

# Fix code style
make fix
```

### Frontend (TypeScript)
```bash
cd apps/frontend
npm run lint
npm run format
```

### Mobile (Dart)
```bash
cd apps/mobile
flutter analyze
```

## 🚦 Development Workflow

1. **Start your day**
   ```bash
   make up
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Make changes and test**
   ```bash
   make test
   ```

4. **Commit and push**
   ```bash
   git add .
   git commit -m "feat: your feature description"
   git push origin feature/your-feature-name
   ```

5. **Create pull request**

6. **End your day**
   ```bash
   make down
   ```

## 📅 Development Phases

### Phase 1: Foundation (Weeks 2-4) - CURRENT
- Backend: Models, controllers, auth, RBAC
- Frontend: Inertia setup, auth pages, dashboard
- Mobile: Auth flow, offline DB, API client

### Phase 2: Core Features (Weeks 5-8)
- GPS tracking
- Work tickets
- GP55 logbook
- Driver/vehicle management

### Phase 3: Advanced Features (Weeks 9-12)
- Maintenance scheduling
- Fuel management
- Reporting
- Geo-fencing

### Phase 4: Integrations (Weeks 13-16)
- NTSA integration
- IFMIS integration
- CMTE integration

## ✅ Checklist Before You Start Coding

- [ ] Docker Desktop is running
- [ ] Ran `./scripts/dev/setup.sh` successfully
- [ ] All containers are up: `docker-compose ps`
- [ ] Can access backend: http://localhost:8000
- [ ] Can access pgAdmin: http://localhost:5050
- [ ] Read [PROJECT_AUDIT.md](PROJECT_AUDIT.md)
- [ ] Read [ARCHITECTURE.md](ARCHITECTURE.md)
- [ ] Understand your assigned module
- [ ] Know how to run tests: `make test`
- [ ] Know how to view logs: `make logs`

## 🎉 You're Ready!

You now have:
- ✅ A fully configured development environment
- ✅ All services running
- ✅ Complete documentation
- ✅ Development tools and scripts
- ✅ Understanding of the project

**Next Step:** Review [PROJECT_AUDIT.md](PROJECT_AUDIT.md) to see what needs to be built, then start coding!

---

**Questions?** Check the documentation or ask the team.  
**Issues?** See [SETUP.md](SETUP.md) troubleshooting section.  
**Ready to code?** Run `make up` and start building! 🚀
