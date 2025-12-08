#!/bin/bash

echo "🔍 Setting up Audit Logging"
echo "=========================="
echo ""

# Step 1: Publish configuration
echo "Step 1: Publishing Spatie Activity Log configuration..."
docker exec gfms_app php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
docker exec gfms_app php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"

echo ""
echo "Step 2: Running migrations..."
docker exec gfms_app php artisan migrate

echo ""
echo "✅ Audit logging setup complete!"
echo ""
echo "Next: Run the backend to start logging activities"
