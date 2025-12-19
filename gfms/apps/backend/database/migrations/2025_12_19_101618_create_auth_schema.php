<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop schema first to ensure a clean start for migrate:fresh
        DB::statement('DROP SCHEMA IF EXISTS auth CASCADE');
        
        // Create auth schema
        DB::statement('CREATE SCHEMA auth');
        
        // Create organizations table in auth schema
        Schema::create('auth.organizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('type'); // ministry, department, agency, county
            $table->uuid('parent_id')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->integer('level')->default(1);
            $table->integer('hierarchical_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Add foreign key constraint for parent_id after table creation
        Schema::table('auth.organizations', function (Blueprint $table) {
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('set null');
        });
        
        // Create users table in auth schema
        Schema::create('auth.users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('personal_number')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->uuid('organization_id')->nullable();
            $table->string('job_group')->nullable();
            $table->string('position')->nullable();
            $table->integer('hierarchical_level')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('set null');
        });
        
        // Create password reset tokens table
        Schema::create('auth.password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
        
        // Create personal access tokens table
        Schema::create('auth.personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->string('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth.personal_access_tokens');
        Schema::dropIfExists('auth.password_reset_tokens');
        Schema::dropIfExists('auth.users');
        Schema::dropIfExists('auth.organizations');
        DB::statement('DROP SCHEMA IF EXISTS auth CASCADE');
    }
};
