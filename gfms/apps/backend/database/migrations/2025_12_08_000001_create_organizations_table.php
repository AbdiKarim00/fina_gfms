<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 50)->unique();
            $table->enum('type', ['ministry', 'department', 'agency', 'county']);
            $table->foreignId('parent_id')->nullable()->constrained('organizations')->onDelete('cascade');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index('code');
            $table->index('type');
            $table->index('is_active');
            $table->index('parent_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizations');
    }
};
