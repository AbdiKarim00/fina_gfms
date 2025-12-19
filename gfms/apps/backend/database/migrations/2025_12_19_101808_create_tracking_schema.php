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
        // Create tracking schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS tracking');
        
        // Create GPS logs table in tracking schema (partitioned by month)
        Schema::create('tracking.gps_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->geometry('location'); // PostGIS geometry point
            $table->decimal('speed', 8, 2);
            $table->decimal('heading', 8, 2);
            $table->decimal('altitude', 8, 2);
            $table->timestamp('logged_at');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->index('logged_at');
            $table->index('vehicle_id');
        });
        
        // Create geo-fences table in tracking schema
        Schema::create('tracking.geo_fences', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->geometry('boundary'); // PostGIS geometry polygon
            $table->string('type'); // restricted, authorized, monitoring
            $table->uuid('created_by');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('created_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
        
        // Create geo-fence violations table in tracking schema
        Schema::create('tracking.geo_fence_violations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('geo_fence_id');
            $table->uuid('vehicle_id');
            $table->uuid('driver_id');
            $table->geometry('violation_location'); // PostGIS geometry point
            $table->timestamp('violated_at');
            $table->text('remarks')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->uuid('resolved_by')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('geo_fence_id')
                  ->references('id')
                  ->on('tracking.geo_fences')
                  ->onDelete('cascade');
                  
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('cascade');
                  
            $table->foreign('resolved_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('set null');
        });
        
        // Create routes table in tracking schema
        Schema::create('tracking.routes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->geometry('path'); // PostGIS geometry linestring
            $table->uuid('organization_id');
            $table->uuid('created_by');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
                  
            $table->foreign('created_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking.routes');
        Schema::dropIfExists('tracking.geo_fence_violations');
        Schema::dropIfExists('tracking.geo_fences');
        Schema::dropIfExists('tracking.gps_logs');
        DB::statement('DROP SCHEMA IF EXISTS tracking CASCADE');
    }
};;
