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
        Schema::create('ward_nurse_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ward_id')
                ->constrained('wards')
                ->cascadeOnDelete();
            $table->foreignId('user_id')        // nurse (users table)
                ->constrained('users')
                ->cascadeOnDelete();
            $table->enum('shift', ['Morning', 'Evening', 'Night']);
            $table->date('start_date');
            $table->date('end_date')->nullable(); // null = permanent
            $table->boolean('is_active')->default(true);
            $table->foreignId('assigned_by')
                ->constrained('users');
            $table->timestamps();

            // Index for quick lookup
            $table->index(['user_id', 'is_active']);
            $table->index(['ward_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ward_nurse_assignments');
    }
};
