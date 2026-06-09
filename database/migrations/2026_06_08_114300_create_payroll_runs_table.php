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
        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();

            $table->string('run_number')->unique();          // PR-2025-06
            $table->year('year');
            $table->tinyInteger('month');                    // 1-12
            $table->string('month_name');                    // June 2025

            $table->enum('status', [
                'Draft',
                'Processing',
                'Processed',
                'Approved',
                'Paid',
                'Cancelled',
            ])->default('Draft');

            // ── Summary ──────────────────────────────────────────────
            $table->integer('total_employees')->default(0);
            $table->decimal('total_gross', 14, 2)->default(0);
            $table->decimal('total_deductions', 12, 2)->default(0);
            $table->decimal('total_net', 14, 2)->default(0);

            // ── Dates ────────────────────────────────────────────────
            $table->date('payment_date')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('approved_at')->nullable();

            // ── Who ──────────────────────────────────────────────────
            $table->foreignId('created_by')
                ->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')
                ->nullable()->constrained('users')->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['year', 'month']);
            $table->index(['year', 'month']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_runs');
    }
};
