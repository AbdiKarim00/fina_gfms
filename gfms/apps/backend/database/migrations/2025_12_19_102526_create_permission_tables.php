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
        // Create permissions schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS permissions');
        
        // Create permissions table
        Schema::create('permissions.permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });
        
        // Create roles table
        Schema::create('permissions.roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
            
            $table->unique(['name', 'guard_name']);
        });
        
        // Create model_has_permissions table
        Schema::create('permissions.model_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->string('model_type');
            $table->uuid('model_id');
            
            $table->index(['model_id', 'model_type']);
            
            $table->primary(['permission_id', 'model_id', 'model_type'],
                'model_has_permissions_permission_model_type_primary');
                
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions.permissions')
                ->onDelete('cascade');
        });
        
        // Create model_has_roles table
        Schema::create('permissions.model_has_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id');
            $table->string('model_type');
            $table->uuid('model_id');
            
            $table->index(['model_id', 'model_type']);
            
            $table->primary(['role_id', 'model_id', 'model_type'],
                'model_has_roles_role_model_type_primary');
                
            $table->foreign('role_id')
                ->references('id')
                ->on('permissions.roles')
                ->onDelete('cascade');
        });
        
        // Create role_has_permissions table
        Schema::create('permissions.role_has_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('permission_id');
            $table->unsignedBigInteger('role_id');
            
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions.permissions')
                ->onDelete('cascade');
                
            $table->foreign('role_id')
                ->references('id')
                ->on('permissions.roles')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions.role_has_permissions');
        Schema::dropIfExists('permissions.model_has_roles');
        Schema::dropIfExists('permissions.model_has_permissions');
        Schema::dropIfExists('permissions.roles');
        Schema::dropIfExists('permissions.permissions');
        DB::statement('DROP SCHEMA IF EXISTS permissions CASCADE');
    }
};;
