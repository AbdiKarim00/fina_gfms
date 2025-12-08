#!/bin/bash
# GFMS Test Runner Script

set -e

GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

echo "🧪 Running GFMS Tests"
echo "===================="
echo ""

# Check if specific test file is provided
if [ -n "$1" ]; then
    echo -e "${YELLOW}Running specific test: $1${NC}"
    docker-compose exec -T app php artisan test "$1"
else
    echo "Running all tests..."
    docker-compose exec -T app php artisan test --parallel
fi

echo ""
echo -e "${GREEN}✅ Tests complete!${NC}"
