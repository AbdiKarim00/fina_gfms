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
        // Create fleet schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS fleet');
        
        // Create drivers table in fleet schema
        Schema::create('fleet.drivers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('personal_number')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('license_number')->unique();
            $table->date('license_expiry_date');
            $table->string('license_class');
            $table->uuid('organization_id');
            $table->string('status'); // active, suspended, retired
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
        });
        
        // Create vehicles table in fleet schema
        Schema::create('fleet.vehicles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('color');
            $table->string('chassis_number')->unique();
            $table->string('engine_number')->unique();
            $table->string('fuel_type');
            $table->decimal('engine_capacity', 8, 2);
            $table->string('transmission_type');
            $table->string('category');
            $table->uuid('organization_id');
            $table->uuid('assigned_driver_id')->nullable();
            $table->string('status'); // available, assigned, maintenance, disposed
            $table->date('acquisition_date');
            $table->decimal('purchase_price', 15, 2);
            $table->string('procurement_method');
            $table->text('description')->nullable();
            $table->geometry('location', 4326)->nullable(); // PostGIS geometry column
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
                  
            $table->foreign('assigned_driver_id')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('set null');
        });
        
        // Create vehicle assignments table in fleet schema
        Schema::create('fleet.vehicle_assignments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('driver_id');
            $table->uuid('assigned_by');
            $table->date('assignment_date');
            $table->date('expected_return_date')->nullable();
            $table->date('actual_return_date')->nullable();
            $table->string('purpose');
            $table->text('remarks')->nullable();
            $table->string('status'); // active, completed, cancelled
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('cascade');
                  
            $table->foreign('assigned_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
        
        // Create vehicle documents table in fleet schema
        Schema::create('fleet.vehicle_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->string('document_type'); // insurance, logbook, fitness, etc.
            $table->string('document_number')->nullable();
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('file_path')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
        });
        
        // Create disposal requests table in fleet schema
        Schema::create('fleet.disposal_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('requested_by');
            $table->text('reason');
            $table->decimal('estimated_value', 15, 2);
            $table->string('disposal_method'); // auction, transfer, scrap
            $table->string('status'); // pending, approved, rejected, completed
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('requested_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
                  
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fleet.disposal_requests');
        Schema::dropIfExists('fleet.vehicle_documents');
        Schema::dropIfExists('fleet.vehicle_assignments');
        Schema::dropIfExists('fleet.vehicles');
        Schema::dropIfExists('fleet.drivers');
        DB::statement('DROP SCHEMA IF EXISTS fleet CASCADE');
    }
};;
