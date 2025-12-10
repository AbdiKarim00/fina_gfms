#!/bin/bash

# GFMS Native Setup Script
# Replaces Docker with native macOS services

set -e

echo "🚀 GFMS Native Setup - Docker Alternative"
echo "========================================"
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Check if Homebrew is installed
if ! command -v brew &> /dev/null; then
    print_error "Homebrew is required but not installed."
    echo "Install it from: https://brew.sh"
    exit 1
fi

print_success "Homebrew is installed"

# Update Homebrew
print_status "Updating Homebrew..."
brew update

# Install PHP 8.2
print_status "Installing PHP 8.2..."
if ! brew list php@8.2 &> /dev/null; then
    brew install php@8.2
    print_success "PHP 8.2 installed"
else
    print_warning "PHP 8.2 already installed"
fi

# Install Composer
print_status "Installing Composer..."
if ! command -v composer &> /dev/null; then
    brew install composer
    print_success "Composer installed"
else
    print_warning "Composer already installed"
fi

# Install PostgreSQL
print_status "Installing PostgreSQL 15..."
if ! brew list postgresql@15 &> /dev/null; then
    brew install postgresql@15
    print_success "PostgreSQL 15 installed"
else
    print_warning "PostgreSQL 15 already installed"
fi

# Install PostGIS
print_status "Installing PostGIS..."
if ! brew list postgis &> /dev/null; then
    brew install postgis
    print_success "PostGIS installed"
else
    print_warning "PostGIS already installed"
fi

# Install Redis
print_status "Installing Redis..."
if ! brew list redis &> /dev/null; then
    brew install redis
    print_success "Redis installed"
else
    print_warning "Redis already installed"
fi

# Start services
print_status "Starting PostgreSQL service..."
brew services start postgresql@15

print_status "Starting Redis service..."
brew services start redis

# Wait for PostgreSQL to start
print_status "Waiting for PostgreSQL to start..."
sleep 3

# Create database and user
print_status "Setting up database..."
if createdb gfms 2>/dev/null; then
    print_success "Database 'gfms' created"
else
    print_warning "Database 'gfms' already exists"
fi

# Create user and grant permissions
psql gfms -c "CREATE USER gfms WITH PASSWORD 'gfms';" 2>/dev/null || print_warning "User 'gfms' already exists"
psql gfms -c "GRANT ALL PRIVILEGES ON DATABASE gfms TO gfms;" 2>/dev/null
psql gfms -c "ALTER USER gfms CREATEDB;" 2>/dev/null
psql gfms -c "GRANT ALL ON SCHEMA public TO gfms;" 2>/dev/null

# Enable PostGIS extension
print_status "Enabling PostGIS extension..."
psql gfms -c "CREATE EXTENSION IF NOT EXISTS postgis;" 2>/dev/null

print_success "Database setup complete"

# Setup backend
print_status "Setting up backend..."
cd apps/backend

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Environment file created"
    
    # Update database configuration in .env
    print_status "Updating database configuration..."
    sed -i '' 's/DB_HOST=.*/DB_HOST=127.0.0.1/' .env
    sed -i '' 's/DB_PORT=.*/DB_PORT=5432/' .env
    sed -i '' 's/DB_DATABASE=.*/DB_DATABASE=gfms/' .env
    sed -i '' 's/DB_USERNAME=.*/DB_USERNAME=gfms/' .env
    sed -i '' 's/DB_PASSWORD=.*/DB_PASSWORD=gfms/' .env
    
    # Update Redis configuration
    sed -i '' 's/REDIS_HOST=.*/REDIS_HOST=127.0.0.1/' .env
    sed -i '' 's/REDIS_PORT=.*/REDIS_PORT=6379/' .env
    
    print_success "Environment configured for native setup"
else
    print_warning "Environment file already exists"
fi

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install

# Generate application key
print_status "Generating application key..."
php artisan key:generate

# Run migrations
print_status "Running database migrations..."
php artisan migrate --force

# Seed database
print_status "Seeding database..."
php artisan db:seed --force

# Create storage link
print_status "Creating storage link..."
php artisan storage:link

print_success "Backend setup complete"

# Setup frontend
print_status "Setting up frontend..."
cd ../frontend

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
npm install

# Copy environment file if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Frontend environment file created"
else
    print_warning "Frontend environment file already exists"
fi

print_success "Frontend setup complete"

# Go back to root
cd ../..

# Create startup scripts
print_status "Creating startup scripts..."

# Create start script
cat > start-native.sh << 'EOF'
#!/bin/bash

echo "🚀 Starting GFMS Native Setup..."

# Check and start services
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
echo ""
echo "1. Backend Server:"
echo "   cd gfms/apps/backend && php artisan serve"
echo ""
echo "2. Frontend Server:"
echo "   cd gfms/apps/frontend && npm run dev"
echo ""
echo "3. Queue Worker:"
echo "   cd gfms/apps/backend && php artisan queue:work"
echo ""
echo "4. WebSocket Server (optional):"
echo "   cd gfms/apps/backend && php artisan reverb:start"
echo ""
echo "Access the application at: http://localhost:3000"
EOF

# Create stop script
cat > stop-native.sh << 'EOF'
#!/bin/bash

echo "🛑 Stopping GFMS Native Setup..."

# Stop background services
brew services stop postgresql@15
brew services stop redis

echo "✅ All services stopped!"
echo "Note: Stop development servers with Ctrl+C in their terminals"
EOF

# Make scripts executable
chmod +x start-native.sh
chmod +x stop-native.sh

print_success "Startup scripts created"

# Final instructions
echo ""
echo "🎉 GFMS Native Setup Complete!"
echo "=============================="
echo ""
echo "✅ Services installed and configured:"
echo "   - PHP 8.2 with extensions"
echo "   - PostgreSQL 15 with PostGIS"
echo "   - Redis"
echo "   - Composer"
echo ""
echo "✅ Database created and seeded"
echo "✅ Backend dependencies installed"
echo "✅ Frontend dependencies installed"
echo ""
echo "🚀 To start the system:"
echo "   ./start-native.sh"
echo ""
echo "Then run these in separate terminals:"
echo "1. cd apps/backend && php artisan serve"
echo "2. cd apps/frontend && npm run dev"
echo "3. cd apps/backend && php artisan queue:work"
echo ""
echo "🌐 Access URLs:"
echo "   Frontend: http://localhost:3000"
echo "   Backend:  http://localhost:8000"
echo ""
echo "🔑 Test Login:"
echo "   Email: admin@gfms.go.ke"
echo "   Password: password"
echo ""
echo "📚 For detailed instructions, see: NATIVE_SETUP_GUIDE.md"
echo ""
print_success "Setup complete! Your system should now run much cooler without Docker."