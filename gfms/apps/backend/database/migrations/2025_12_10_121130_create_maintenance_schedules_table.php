<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->string('type'); // routine, repair, inspection, service
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->datetime('scheduled_start');
            $table->datetime('scheduled_end');
            $table->datetime('actual_start')->nullable();
            $table->datetime('actual_end')->nullable();
            $table->text('description');
            $table->text('notes')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->decimal('actual_cost', 10, 2)->nullable();
            $table->foreignId('scheduled_by')->constrained('users');
            $table->foreignId('performed_by')->nullable()->constrained('users');
            $table->string('service_provider')->nullable(); // Workshop/garage name
            $table->timestamps();
            
            $table->index(['vehicle_id', 'status']);
            $table->index(['scheduled_start', 'scheduled_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
