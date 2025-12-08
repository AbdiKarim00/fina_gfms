<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add personal number (UPN) - nullable first, will update existing users
            $table->string('personal_number', 50)->nullable()->after('id');
            
            // Add organization relationship
            $table->foreignId('organization_id')->nullable()->after('personal_number')
                  ->constrained('organizations')->onDelete('restrict');
            
            // Add account status and security fields
            $table->boolean('is_active')->default(true)->after('email_verified_at');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->integer('failed_login_attempts')->default(0)->after('last_login_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            
            // Indexes for performance
            $table->index('organization_id');
            $table->index('is_active');
        });

        // Update existing users with temporary personal numbers (6-digit numeric only)
        // Format: 100001, 100002, etc. (starting from 100000 to ensure 6 digits)
        DB::statement('UPDATE users SET personal_number = LPAD((100000 + id)::text, 6, \'0\') WHERE personal_number IS NULL');

        // Now make personal_number unique and not null
        Schema::table('users', function (Blueprint $table) {
            $table->string('personal_number', 50)->unique()->change();
        });

        // Drop id_number if it exists (replaced by personal_number)
        if (Schema::hasColumn('users', 'id_number')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('id_number');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key if exists
            if (Schema::hasColumn('users', 'organization_id')) {
                $table->dropForeign(['organization_id']);
            }
            
            // Drop indexes if they exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexes = $sm->listTableIndexes('users');
            
            if (isset($indexes['users_organization_id_index'])) {
                $table->dropIndex(['organization_id']);
            }
            if (isset($indexes['users_is_active_index'])) {
                $table->dropIndex(['is_active']);
            }
            
            // Drop columns
            $table->dropColumn([
                'personal_number',
                'organization_id',
                'is_active',
                'last_login_at',
                'failed_login_attempts',
                'locked_until',
            ]);
        });
    }
};
