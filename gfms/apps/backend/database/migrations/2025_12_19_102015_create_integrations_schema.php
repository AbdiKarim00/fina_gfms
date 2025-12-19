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
        // Create integrations schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS integrations');
        
        // Create NTSA sync log table in integrations schema
        Schema::create('integrations.ntsa_sync_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_type'); // vehicle, driver
            $table->uuid('entity_id');
            $table->string('action'); // create, update, delete
            $table->json('payload');
            $table->string('status'); // pending, success, failed
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
        
        // Create IFMIS sync log table in integrations schema
        Schema::create('integrations.ifmis_sync_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_type'); // expenditure, budget
            $table->uuid('entity_id');
            $table->string('action'); // create, update
            $table->json('payload');
            $table->string('status'); // pending, success, failed
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
        
        // Create CMTE sync log table in integrations schema
        Schema::create('integrations.cmte_sync_log', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('entity_type'); // inspection, violation
            $table->uuid('entity_id');
            $table->string('action'); // create, update
            $table->json('payload');
            $table->string('status'); // pending, success, failed
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
        });
        
        // Create fuel provider sync table in integrations schema
        Schema::create('integrations.fuel_provider_sync', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('fuel_transaction_id');
            $table->string('provider_name');
            $table->string('provider_transaction_id');
            $table->json('response_data');
            $table->string('status'); // pending, success, failed
            $table->text('error_message')->nullable();
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();
            
            $table->foreign('fuel_transaction_id')
                  ->references('id')
                  ->on('finance.fuel_transactions')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations.fuel_provider_sync');
        Schema::dropIfExists('integrations.cmte_sync_log');
        Schema::dropIfExists('integrations.ifmis_sync_log');
        Schema::dropIfExists('integrations.ntsa_sync_log');
        DB::statement('DROP SCHEMA IF EXISTS integrations CASCADE');
    }
};;
