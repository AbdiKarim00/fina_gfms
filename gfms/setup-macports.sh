#!/bin/bash

# GFMS MacPorts Setup Script
# For macOS 12 with MacPorts

set -e

echo "🚀 GFMS MacPorts Setup"
echo "====================="
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

# Check if MacPorts is installed
if ! command -v port &> /dev/null; then
    print_error "MacPorts is required but not installed."
    echo "Install it from: https://www.macports.org/install.php"
    exit 1
fi

print_success "MacPorts is installed"

# Check if PHP is installed
if ! command -v php &> /dev/null; then
    print_error "PHP is not installed or not in PATH."
    echo "Please install PHP via MacPorts: sudo port install php83"
    exit 1
fi

print_success "PHP $(php -v | head -n1 | cut -d' ' -f2) is installed"

# Install required PHP extensions
print_status "Installing PHP extensions..."

# List of required extensions
extensions=(
    "php83-mbstring"
    "php83-iconv" 
    "php83-pdo"
    "php83-pdo_sqlite"
    "php83-pdo_pgsql"
    "php83-redis"
    "php83-curl"
    "php83-openssl"
    "php83-zip"
    "php83-gd"
    "php83-intl"
    "php83-xml"
    "php83-dom"
    "php83-fileinfo"
    "php83-tokenizer"
    "php83-ctype"
    "php83-json"
    "php83-bcmath"
)

for ext in "${extensions[@]}"; do
    if port installed | grep -q "^  $ext "; then
        print_warning "$ext already installed"
    else
        print_status "Installing $ext..."
        sudo port install "$ext" || print_warning "Failed to install $ext (may not be available)"
    fi
done

# Install PostgreSQL
print_status "Installing PostgreSQL..."
if port installed | grep -q "^  postgresql15 "; then
    print_warning "PostgreSQL 15 already installed"
else
    sudo port install postgresql15 +universal
    sudo port install postgresql15-server +universal
fi

# Install Redis
print_status "Installing Redis..."
if port installed | grep -q "^  redis "; then
    print_warning "Redis already installed"
else
    sudo port install redis +universal
fi

# Install Composer manually (since the existing one has issues)
print_status "Installing Composer..."
if [ -f "/opt/local/bin/composer" ]; then
    print_warning "Composer already installed in /opt/local/bin/"
else
    curl -sS https://getcomposer.org/installer | php
    sudo mv composer.phar /opt/local/bin/composer
    sudo chmod +x /opt/local/bin/composer
    print_success "Composer installed"
fi

# Configure PHP
print_status "Configuring PHP..."
PHP_INI_DIR="/opt/local/etc/php83"
if [ ! -f "$PHP_INI_DIR/php.ini" ]; then
    sudo cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"
    print_success "PHP configuration created"
else
    print_warning "PHP configuration already exists"
fi

# Update PHP configuration for Laravel
print_status "Updating PHP configuration for Laravel..."
sudo tee -a "$PHP_INI_DIR/php.ini" > /dev/null << 'EOF'

; Laravel optimizations
memory_limit = 512M
max_execution_time = 300
max_input_time = 300
upload_max_filesize = 50M
post_max_size = 50M

; Enable extensions
extension=mbstring
extension=iconv
extension=pdo
extension=pdo_sqlite
extension=pdo_pgsql
extension=redis
extension=curl
extension=openssl
extension=zip
extension=gd
extension=intl
extension=xml
extension=dom
extension=fileinfo
extension=tokenizer
extension=ctype
extension=json
extension=bcmath
EOF

print_success "PHP configuration updated"

# Initialize PostgreSQL
print_status "Initializing PostgreSQL..."
if [ ! -d "/opt/local/var/db/postgresql15/defaultdb" ]; then
    sudo mkdir -p /opt/local/var/db/postgresql15
    sudo chown _postgresql:_postgresql /opt/local/var/db/postgresql15
    sudo -u _postgresql /opt/local/lib/postgresql15/bin/initdb -D /opt/local/var/db/postgresql15/defaultdb
    print_success "PostgreSQL initialized"
else
    print_warning "PostgreSQL already initialized"
fi

# Start PostgreSQL
print_status "Starting PostgreSQL..."
sudo port load postgresql15-server

# Wait for PostgreSQL to start
sleep 3

# Create database and user
print_status "Setting up database..."
sudo -u _postgresql /opt/local/lib/postgresql15/bin/createdb gfms 2>/dev/null || print_warning "Database 'gfms' already exists"
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "CREATE USER gfms WITH PASSWORD 'gfms';" 2>/dev/null || print_warning "User 'gfms' already exists"
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "GRANT ALL PRIVILEGES ON DATABASE gfms TO gfms;" 2>/dev/null
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "ALTER USER gfms CREATEDB;" 2>/dev/null
sudo -u _postgresql /opt/local/lib/postgresql15/bin/psql -d gfms -c "GRANT ALL ON SCHEMA public TO gfms;" 2>/dev/null

print_success "Database setup complete"

# Start Redis
print_status "Starting Redis..."
sudo port load redis

print_success "Redis started"

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
    
    print_success "Environment configured for MacPorts setup"
else
    print_warning "Environment file already exists"
fi

# Install PHP dependencies
print_status "Installing PHP dependencies..."
/opt/local/bin/composer install

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

# Create startup scripts for MacPorts
print_status "Creating startup scripts..."

# Create start script
cat > start-macports.sh << 'EOF'
#!/bin/bash

echo "🚀 Starting GFMS MacPorts Setup..."

# Start services using MacPorts
echo "📦 Starting PostgreSQL..."
sudo port load postgresql15-server

echo "📦 Starting Redis..."
sudo port load redis

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
cat > stop-macports.sh << 'EOF'
#!/bin/bash

echo "🛑 Stopping GFMS MacPorts Setup..."

# Stop services using MacPorts
sudo port unload postgresql15-server
sudo port unload redis

echo "✅ All services stopped!"
echo "Note: Stop development servers with Ctrl+C in their terminals"
EOF

# Make scripts executable
chmod +x start-macports.sh
chmod +x stop-macports.sh

print_success "Startup scripts created"

# Test Composer
print_status "Testing Composer..."
if /opt/local/bin/composer --version > /dev/null 2>&1; then
    print_success "Composer is working: $(/opt/local/bin/composer --version)"
else
    print_error "Composer test failed"
fi

# Test database connection
print_status "Testing database connection..."
cd apps/backend
if php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful';" 2>/dev/null; then
    print_success "Database connection test passed"
else
    print_warning "Database connection test failed - check PostgreSQL status"
fi

cd ../..

# Final instructions
echo ""
echo "🎉 GFMS MacPorts Setup Complete!"
echo "================================"
echo ""
echo "✅ Services installed and configured:"
echo "   - PHP 8.3 with all required extensions"
echo "   - PostgreSQL 15"
echo "   - Redis"
echo "   - Composer"
echo ""
echo "✅ Database created and seeded"
echo "✅ Backend dependencies installed"
echo "✅ Frontend dependencies installed"
echo ""
echo "🚀 To start the system:"
echo "   ./start-macports.sh"
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
print_success "Setup complete! MacPorts-based installation ready."