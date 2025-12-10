#!/bin/bash

echo "🚀 Starting GFMS MacPorts Setup..."

# Start services using MacPorts
echo "📦 Starting PostgreSQL..."
sudo port load postgresql15-server

echo "📦 Starting Redis..."
sudo port load redis

# Wait for services to start
sleep 2

# Test connections
echo "🔍 Testing connections..."

# Test PostgreSQL
if PGPASSWORD=gfms /opt/local/lib/postgresql15/bin/psql -h 127.0.0.1 -U gfms -d gfms -c "SELECT 1;" > /dev/null 2>&1; then
    echo "✅ PostgreSQL connection successful"
else
    echo "❌ PostgreSQL connection failed"
fi

# Test Redis
if /opt/local/bin/redis-cli ping > /dev/null 2>&1; then
    echo "✅ Redis connection successful"
else
    echo "❌ Redis connection failed"
fi

echo ""
echo "✅ Services started!"
echo ""
echo "Now run these commands in separate terminals:"
echo ""
echo "1. Backend Server:"
echo "   cd gfms/apps/backend && php artisan serve"
echo ""
echo "2. Frontend Server:"
echo "   cd gfms/apps/frontend && /opt/local/bin/npm run dev"
echo ""
echo "3. Queue Worker (optional):"
echo "   cd gfms/apps/backend && php artisan queue:work"
echo ""
echo "4. WebSocket Server (optional):"
echo "   cd gfms/apps/backend && php artisan reverb:start"
echo ""
echo "🌐 Access URLs:"
echo "   Frontend: http://localhost:3000"
echo "   Backend:  http://localhost:8000"
echo ""
echo "🔑 Test Login:"
echo "   Personal Number: 100000"
echo "   Password: password"