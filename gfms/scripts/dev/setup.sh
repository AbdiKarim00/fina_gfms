#!/bin/bash
# GFMS Development Environment Setup Script
# This script sets up the complete development environment

set -e  # Exit on error

echo "🚀 GFMS Development Environment Setup"
echo "======================================"
echo ""

# Colors for output
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Check if Docker is running
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}❌ Docker is not running. Please start Docker Desktop.${NC}"
    exit 1
fi

echo -e "${GREEN}✓ Docker is running${NC}"
echo ""

# Step 1: Build Docker containers
echo "📦 Building Docker containers..."
docker-compose build --no-cache
echo -e "${GREEN}✓ Containers built${NC}"
echo ""

# Step 2: Start services
echo "🔧 Starting services..."
docker-compose up -d
echo -e "${GREEN}✓ Services started${NC}"
echo ""

# Wait for PostgreSQL to be ready
echo "⏳ Waiting for PostgreSQL to be ready..."
sleep 10
until docker-compose exec -T postgres pg_isready -U gfms > /dev/null 2>&1; do
    echo "   Waiting for database..."
    sleep 2
done
echo -e "${GREEN}✓ PostgreSQL is ready${NC}"
echo ""

# Step 3: Install backend dependencies
echo "📚 Installing backend dependencies..."
docker-compose exec -T app composer install --no-interaction
echo -e "${GREEN}✓ Backend dependencies installed${NC}"
echo ""

# Step 4: Copy .env file if it doesn't exist
if [ ! -f "apps/backend/.env" ]; then
    echo "📝 Creating .env file..."
    docker-compose exec -T app cp .env.example .env
    echo -e "${GREEN}✓ .env file created${NC}"
else
    echo -e "${YELLOW}⚠ .env file already exists, skipping${NC}"
fi
echo ""

# Step 5: Generate application key
echo "🔑 Generating application key..."
docker-compose exec -T app php artisan key:generate --ansi
echo -e "${GREEN}✓ Application key generated${NC}"
echo ""

# Step 6: Run database migrations
echo "🗄️  Running database migrations..."
docker-compose exec -T app php artisan migrate --force
echo -e "${GREEN}✓ Migrations completed${NC}"
echo ""

# Step 7: Seed database (optional)
read -p "Do you want to seed the database with test data? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌱 Seeding database..."
    docker-compose exec -T app php artisan db:seed
    echo -e "${GREEN}✓ Database seeded${NC}"
fi
echo ""

# Step 8: Clear and cache config
echo "🧹 Clearing and caching configuration..."
docker-compose exec -T app php artisan config:clear
docker-compose exec -T app php artisan cache:clear
echo -e "${GREEN}✓ Cache cleared${NC}"
echo ""

# Display service URLs
echo ""
echo "======================================"
echo -e "${GREEN}✅ Setup Complete!${NC}"
echo "======================================"
echo ""
echo "📍 Service URLs:"
echo "   Backend API:    http://localhost:8000"
echo "   MailHog:        http://localhost:8025"
echo "   pgAdmin:        http://localhost:5050"
echo "   WebSocket:      ws://localhost:8080"
echo ""
echo "🔐 pgAdmin Credentials:"
echo "   Email:          admin@gfms.go.ke"
echo "   Password:       admin"
echo ""
echo "📊 Database Connection:"
echo "   Host:           localhost"
echo "   Port:           5433"
echo "   Database:       gfms"
echo "   Username:       gfms"
echo "   Password:       gfms"
echo ""
echo "🎯 Next Steps:"
echo "   1. Test API:    curl http://localhost:8000/api/health"
echo "   2. View logs:   docker-compose logs -f"
echo "   3. Run tests:   docker-compose exec app php artisan test"
echo "   4. Access shell: docker-compose exec app bash"
echo ""
echo "📖 Documentation:"
echo "   Setup Guide:    gfms/SETUP.md"
echo "   Quick Ref:      gfms/QUICK_REFERENCE.md"
echo "   Blueprint:      gfms/INITIALIZATION_AUDIT_AND_BLUEPRINT.md"
echo ""
echo -e "${GREEN}Happy coding! 🚀${NC}"
