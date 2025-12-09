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
        Schema::table('vehicles', function (Blueprint $table) {
            // Add missing fields to align with frontend
            $table->string('chassis_number')->nullable()->after('engine_number');
            $table->integer('mileage')->nullable()->after('fuel_consumption_rate');
            $table->integer('capacity')->nullable()->after('mileage');
            $table->year('purchase_year')->nullable()->after('purchase_price');
            $table->string('current_location')->nullable()->after('purchase_year');
            $table->string('original_location')->nullable()->after('current_location');
            $table->string('responsible_officer')->nullable()->after('original_location');
            $table->boolean('has_log_book')->default(true)->after('responsible_officer');
            $table->foreignId('organization_id')->nullable()->after('has_log_book')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn([
                'chassis_number',
                'mileage',
                'capacity',
                'purchase_year',
                'current_location',
                'original_location',
                'responsible_officer',
                'has_log_book',
                'organization_id',
            ]);
        });
    }
};
