<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('id_number')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Vehicles Table
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('color')->nullable();
            $table->string('vin')->unique()->nullable();
            $table->string('engine_number')->unique()->nullable();
            $table->string('fuel_type');
            $table->decimal('fuel_consumption_rate', 8, 2)->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('purchase_price', 15, 2)->nullable();
            $table->enum('status', ['active', 'inactive', 'maintenance', 'disposed'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Drivers Table
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_number')->unique();
            $table->date('license_expiry_date');
            $table->enum('license_class', ['A', 'B', 'C', 'D', 'E', 'F']);
            $table->date('date_hired')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended', 'on_leave'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Vehicle Assignments
        Schema::create('vehicle_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->date('assigned_date');
            $table->date('returned_date')->nullable();
            $table->text('purpose');
            $table->string('assigned_by');
            $table->string('received_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Maintenance Records
        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->date('maintenance_date');
            $table->string('maintenance_type');
            $table->integer('odometer_reading');
            $table->text('description');
            $table->decimal('cost', 15, 2);
            $table->string('service_provider');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Fuel Records
        Schema::create('fuel_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('drivers')->onDelete('cascade');
            $table->date('date');
            $table->decimal('liters', 10, 2);
            $table->decimal('cost_per_liter', 10, 2);
            $table->decimal('total_cost', 15, 2);
            $table->integer('odometer_reading');
            $table->string('fuel_station');
            $table->string('receipt_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fuel_records');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('vehicle_assignments');
        Schema::dropIfExists('drivers');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('users');
    }
};
