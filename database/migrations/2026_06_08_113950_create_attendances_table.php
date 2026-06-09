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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');

            // ── Time ────────────────────────────────────────────────
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('working_minutes')->default(0);  // Total time worked
            $table->integer('overtime_minutes')->default(0); // Extra time
            $table->integer('late_minutes')->default(0);     // Late arrival

            // ── Status ──────────────────────────────────────────────
            $table->enum('status', [
                'Present',
                'Absent',
                'Late',
                'Half Day',
                'On Leave',
                'Holiday',
                'Weekend',
                'Work From Home',
            ])->default('Present');

            // ── Source ──────────────────────────────────────────────
            $table->enum('source', [
                'Manual',
                'Biometric',
                'System',
            ])->default('Manual');

            $table->text('notes')->nullable();

            // ── Approval ────────────────────────────────────────────
            $table->boolean('is_regularized')->default(false); // Manual correction
            $table->foreignId('regularized_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();
            $table->text('regularization_reason')->nullable();

            $table->timestamps();

            $table->unique(['employee_id', 'date']);
            $table->index(['employee_id', 'date']);
            $table->index(['date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
