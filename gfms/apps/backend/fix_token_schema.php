<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== Fixing Personal Access Tokens Schema ===\n\n";

try {
    // Check current table structure
    echo "1. Checking current table structure...\n";
    $columns = Schema::getColumnListing('auth.personal_access_tokens');
    echo "   Current columns: " . implode(', ', $columns) . "\n\n";

    // Drop and recreate table with UUID support
    echo "2. Dropping and recreating table with UUID support...\n";
    Schema::dropIfExists('auth.personal_access_tokens');
    
    Schema::create('auth.personal_access_tokens', function ($table) {
        $table->id();
        $table->uuidMorphs('tokenable');
        $table->string('name');
        $table->string('token', 64)->unique();
        $table->text('abilities')->nullable();
        $table->timestamp('last_used_at')->nullable();
        $table->timestamp('expires_at')->nullable();
        $table->timestamps();
    });
    
    echo "   Table recreated with UUID morphs support\n\n";

    // Verify new structure
    echo "3. Verifying new table structure...\n";
    $newColumns = Schema::getColumnListing('auth.personal_access_tokens');
    echo "   New columns: " . implode(', ', $newColumns) . "\n\n";

    // Test the schema with a simple query
    echo "4. Testing schema with sample data...\n";
    $tableInfo = DB::select("SELECT column_name, data_type FROM information_schema.columns WHERE table_name = 'personal_access_tokens' AND table_schema = 'auth' ORDER BY ordinal_position");
    
    echo "   Column details:\n";
    foreach ($tableInfo as $column) {
        echo "   - {$column->column_name}: {$column->data_type}\n";
    }
    
    echo "\n=== Schema Fix Complete ===\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
