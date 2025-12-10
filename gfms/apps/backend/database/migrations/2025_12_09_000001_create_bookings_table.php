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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // Foreign keys
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->foreignId('requester_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('driver_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Booking details
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('purpose');
            $table->string('destination');
            $table->integer('passengers')->default(1);
            
            // Status and priority
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'cancelled'])
                  ->default('pending');
            $table->enum('priority', ['high', 'medium', 'low'])->default('medium');
            
            // Approval tracking
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Additional info
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('vehicle_id');
            $table->index('requester_id');
            $table->index('driver_id');
            $table->index('status');
            $table->index('priority');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
