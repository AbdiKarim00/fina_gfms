#!/bin/bash
# GFMS Database Reset Script
# Drops all tables, re-runs migrations, and optionally seeds data

set -e

echo "🔄 GFMS Database Reset"
echo "====================="
echo ""
echo "⚠️  WARNING: This will delete ALL data in the database!"
read -p "Are you sure you want to continue? (yes/no) " -r
echo ""

if [[ ! $REPLY =~ ^[Yy][Ee][Ss]$ ]]; then
    echo "❌ Reset cancelled"
    exit 0
fi

GREEN='\033[0;32m'
NC='\033[0m'

echo "🗑️  Dropping all tables..."
docker-compose exec -T app php artisan migrate:fresh --force
echo -e "${GREEN}✓ Database reset${NC}"
echo ""

read -p "Do you want to seed the database? (y/n) " -n 1 -r
echo ""
if [[ $REPLY =~ ^[Yy]$ ]]; then
    echo "🌱 Seeding database..."
    docker-compose exec -T app php artisan db:seed
    echo -e "${GREEN}✓ Database seeded${NC}"
fi

echo ""
echo -e "${GREEN}✅ Database reset complete!${NC}"
