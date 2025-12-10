#!/bin/bash

# GFMS Simple Setup Script
# Uses SQLite and file-based caching to avoid complex installations

set -e

echo "🚀 GFMS Simple Setup (SQLite + File Cache)"
echo "=========================================="
echo ""

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

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

# Check prerequisites
print_status "Checking prerequisites..."

if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH."
    exit 1
fi

if ! command -v node &> /dev/null; then
    print_error "Node.js is not installed."
    exit 1
fi

if ! command -v composer &> /dev/null; then
    print_error "Composer is not installed."
    exit 1
fi

print_success "All prerequisites found"
print_status "PHP: $(php -v | head -n1)"
print_status "Node.js: $(node -v)"
print_status "Composer: $(composer --version | head -n1)"

# Setup backend
print_status "Setting up backend..."
cd apps/backend

# Copy environment file
if [ ! -f .env ]; then
    cp .env.example .env
    print_success "Environment file created"
else
    print_warning "Environment file already exists"
fi

# Configure for SQLite and file-based caching
print_status "Configuring for SQLite and file cache..."
cat > .env << 'EOF'
APP_NAME="Kenya GFMS"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

# SQLite Database (no server needed)
DB_CONNECTION=sqlite
DB_DATABASE=/Users/abu/Final GFMS/gfms/apps/backend/database/database.sqlite
DB_FOREIGN_KEYS=true

# File-based cache and sessions (no Redis needed)
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# File-based mail for development
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@gfms.go.ke"
MAIL_FROM_NAME="${APP_NAME}"

# SMS Configuration (for testing)
SMS_PROVIDER=log
AFRICASTALKING_USERNAME=sandbox
AFRICASTALKING_API_KEY=test-key
AFRICASTALKING_FROM=GFMS

# Disable WebSocket for simplicity
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=
REVERB_PORT=
REVERB_SCHEME=

VITE_REVERB_APP_KEY=
VITE_REVERB_HOST=
VITE_REVERB_PORT=
VITE_REVERB_SCHEME=
EOF

print_success "Environment configured for simple setup"

# Create SQLite database
print_status "Creating SQLite database..."
touch database/database.sqlite
chmod 664 database/database.sqlite
print_success "SQLite database created"

# Install PHP dependencies
print_status "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

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

# Cache configuration
print_status "Caching configuration..."
php artisan config:cache

print_success "Backend setup complete"

# Setup frontend
print_status "Setting up frontend..."
cd ../frontend

# Install Node.js dependencies
print_status "Installing Node.js dependencies..."
npm install

# Copy environment file
if [ ! -f .env ]; then
    cat > .env << 'EOF'
VITE_APP_NAME="Kenya GFMS"
VITE_API_BASE_URL=http://localhost:8000/api/v1
VITE_APP_URL=http://localhost:3000

# Disable WebSocket for simplicity
VITE_REVERB_APP_KEY=
VITE_REVERB_HOST=
VITE_REVERB_PORT=
VITE_REVERB_SCHEME=
EOF
    print_success "Frontend environment file created"
else
    print_warning "Frontend environment file already exists"
fi

print_success "Frontend setup complete"

# Go back to root
cd ../..

# Create simple startup scripts
print_status "Creating startup scripts..."

# Create start script
cat > start-simple.sh << 'EOF'
#!/bin/bash

echo "🚀 Starting GFMS Simple Setup..."
echo ""
echo "No services to start (using SQLite + file cache)"
echo ""
echo "Run these commands in separate terminals:"
echo ""
echo "1. Backend Server:"
echo "   cd gfms/apps/backend && php artisan serve"
echo ""
echo "2. Frontend Server:"
echo "   cd gfms/apps/frontend && npm run dev"
echo ""
echo "Note: Queue processing is synchronous (no separate worker needed)"
echo ""
echo "Access the application at: http://localhost:3000"
EOF

# Create stop script
cat > stop-simple.sh << 'EOF'
#!/bin/bash

echo "🛑 Stopping GFMS Simple Setup..."
echo ""
echo "No services to stop (using SQLite + file cache)"
echo "Just stop the development servers with Ctrl+C in their terminals"
EOF

# Make scripts executable
chmod +x start-simple.sh
chmod +x stop-simple.sh

print_success "Startup scripts created"

# Test database connection
print_status "Testing database connection..."
cd apps/backend
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" 2>/dev/null; then
    print_success "Database connection test passed"
else
    print_warning "Database connection test failed"
fi

cd ../..

# Final instructions
echo ""
echo "🎉 GFMS Simple Setup Complete!"
echo "============================="
echo ""
echo "✅ Configuration:"
echo "   - SQLite database (no server needed)"
echo "   - File-based cache and sessions"
echo "   - Synchronous queue processing"
echo "   - Log-based mail and SMS"
echo ""
echo "✅ Backend dependencies installed"
echo "✅ Frontend dependencies installed"
echo "✅ Database created and seeded"
echo ""
echo "🚀 To start the system:"
echo "   ./start-simple.sh"
echo ""
echo "Then run these in separate terminals:"
echo "1. cd apps/backend && php artisan serve"
echo "2. cd apps/frontend && npm run dev"
echo ""
echo "🌐 Access URLs:"
echo "   Frontend: http://localhost:3000"
echo "   Backend:  http://localhost:8000"
echo ""
echo "🔑 Test Login:"
echo "   Email: admin@gfms.go.ke"
echo "   Password: password"
echo ""
echo "📊 Benefits of Simple Setup:"
echo "   - No external services needed"
echo "   - Minimal resource usage"
echo "   - Perfect for development"
echo "   - Easy to debug"
echo ""
print_success "Setup complete! Much simpler and lighter than Docker."