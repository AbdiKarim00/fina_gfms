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
        // Create maintenance schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS maintenance');
        
        // Create maintenance schedules table in maintenance schema
        Schema::create('maintenance.schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->string('type'); // routine, preventive, corrective
            $table->string('service_type'); // oil change, tire rotation, brake service, etc.
            $table->integer('interval_km');
            $table->integer('interval_days');
            $table->decimal('cost_estimate', 15, 2);
            $table->uuid('assigned_to')->nullable();
            $table->date('next_due_date');
            $table->integer('next_due_km');
            $table->string('status'); // active, completed, cancelled
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('set null');
        });
        
        // Create work orders table in maintenance schema
        Schema::create('maintenance.work_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('schedule_id')->nullable();
            $table->string('work_order_number')->unique();
            $table->string('type'); // scheduled, unscheduled, emergency
            $table->text('description');
            $table->uuid('requested_by');
            $table->uuid('assigned_to')->nullable();
            $table->decimal('estimated_cost', 15, 2);
            $table->decimal('actual_cost', 15, 2)->nullable();
            $table->date('scheduled_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->string('priority'); // low, medium, high, urgent
            $table->string('status'); // pending, in_progress, completed, cancelled
            $table->text('completion_notes')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('schedule_id')
                  ->references('id')
                  ->on('maintenance.schedules')
                  ->onDelete('set null');
                  
            $table->foreign('requested_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
                  
            $table->foreign('assigned_to')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('set null');
        });
        
        // Create service records table in maintenance schema
        Schema::create('maintenance.service_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_order_id');
            $table->string('service_type');
            $table->string('service_provider');
            $table->date('service_date');
            $table->integer('odometer_reading');
            $table->decimal('cost', 15, 2);
            $table->text('description');
            $table->text('parts_used')->nullable();
            $table->text('technician_notes')->nullable();
            $table->string('invoice_number')->nullable();
            $table->timestamps();
            
            $table->foreign('work_order_id')
                  ->references('id')
                  ->on('maintenance.work_orders')
                  ->onDelete('cascade');
        });
        
        // Create CMTE inspections table in maintenance schema
        Schema::create('maintenance.cmte_inspections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('inspector_id');
            $table->date('inspection_date');
            $table->string('inspection_type'); // monthly, quarterly, annual
            $table->text('findings');
            $table->text('recommendations');
            $table->string('status'); // passed, failed, conditional
            $table->date('next_inspection_date');
            $table->text('corrective_actions')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('inspector_id')
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
        Schema::dropIfExists('maintenance.cmte_inspections');
        Schema::dropIfExists('maintenance.service_records');
        Schema::dropIfExists('maintenance.work_orders');
        Schema::dropIfExists('maintenance.schedules');
        DB::statement('DROP SCHEMA IF EXISTS maintenance CASCADE');
    }
};;
