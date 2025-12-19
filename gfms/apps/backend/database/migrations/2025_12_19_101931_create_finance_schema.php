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
        // Create finance schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS finance');
        
        // Create budgets table in finance schema
        Schema::create('finance.budgets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organization_id');
            $table->string('fiscal_year');
            $table->string('category'); // fleet, maintenance, fuel, insurance
            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('utilized_amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->uuid('approved_by');
            $table->timestamp('approved_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
                  
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
        
        // Create expenditures table in finance schema
        Schema::create('finance.expenditures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('budget_id')->nullable();
            $table->uuid('vehicle_id')->nullable();
            $table->uuid('work_order_id')->nullable();
            $table->uuid('organization_id');
            $table->string('expenditure_type'); // purchase, maintenance, fuel, insurance
            $table->decimal('amount', 15, 2);
            $table->date('transaction_date');
            $table->string('reference_number')->nullable();
            $table->text('description');
            $table->uuid('authorized_by');
            $table->timestamps();
            
            $table->foreign('budget_id')
                  ->references('id')
                  ->on('finance.budgets')
                  ->onDelete('set null');
                  
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('set null');
                  
            $table->foreign('work_order_id')
                  ->references('id')
                  ->on('maintenance.work_orders')
                  ->onDelete('set null');
                  
            $table->foreign('organization_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
                  
            $table->foreign('authorized_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
        
        // Create fuel transactions table in finance schema
        Schema::create('finance.fuel_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vehicle_id');
            $table->uuid('driver_id');
            $table->decimal('quantity_liters', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('total_cost', 15, 2);
            $table->string('fuel_type');
            $table->integer('odometer_reading');
            $table->uuid('station_id')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamp('transaction_datetime');
            $table->uuid('authorized_by');
            $table->timestamps();
            
            $table->foreign('vehicle_id')
                  ->references('id')
                  ->on('fleet.vehicles')
                  ->onDelete('cascade');
                  
            $table->foreign('driver_id')
                  ->references('id')
                  ->on('fleet.drivers')
                  ->onDelete('cascade');
                  
            $table->foreign('authorized_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
        });
        
        // Create cost allocations table in finance schema
        Schema::create('finance.cost_allocations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('expenditure_id');
            $table->uuid('department_id');
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 15, 2);
            $table->text('justification')->nullable();
            $table->timestamps();
            
            $table->foreign('expenditure_id')
                  ->references('id')
                  ->on('finance.expenditures')
                  ->onDelete('cascade');
                  
            $table->foreign('department_id')
                  ->references('id')
                  ->on('auth.organizations')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('finance.cost_allocations');
        Schema::dropIfExists('finance.fuel_transactions');
        Schema::dropIfExists('finance.expenditures');
        Schema::dropIfExists('finance.budgets');
        DB::statement('DROP SCHEMA IF EXISTS finance CASCADE');
    }
};;
