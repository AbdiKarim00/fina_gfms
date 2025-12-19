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
        // Create audit schema
        DB::statement('CREATE SCHEMA IF NOT EXISTS audit');
        
        // Note: The activity_log table is created by the Spatie Activitylog package
        // We're just ensuring the schema exists for it
        
        // Create system events table in audit schema
        Schema::create('audit.system_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('event_type');
            $table->string('source'); // backend, frontend, mobile
            $table->uuid('user_id')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('metadata')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('set null');
                  
            $table->index('event_type');
            $table->index('occurred_at');
        });
        
        // Create data changes table in audit schema
        Schema::create('audit.data_changes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('table_name');
            $table->uuid('record_id');
            $table->string('operation'); // insert, update, delete
            $table->uuid('changed_by');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->text('reason')->nullable();
            $table->timestamp('changed_at');
            $table->timestamps();
            
            $table->foreign('changed_by')
                  ->references('id')
                  ->on('auth.users')
                  ->onDelete('cascade');
                  
            $table->index(['table_name', 'record_id']);
            $table->index('changed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit.data_changes');
        Schema::dropIfExists('audit.system_events');
        DB::statement('DROP SCHEMA IF EXISTS audit CASCADE');
    }
};;
