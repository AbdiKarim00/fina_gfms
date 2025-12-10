#!/bin/bash

echo "🛑 Stopping GFMS MacPorts Setup..."

# Stop services using MacPorts
echo "📦 Stopping PostgreSQL..."
sudo port unload postgresql15-server

echo "📦 Stopping Redis..."
sudo port unload redis

echo "✅ All services stopped!"
echo "Note: Stop development servers with Ctrl+C in their terminals"