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
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->string('leave_number')->unique();         // LR-00001

            // ── Relations ───────────────────────────────────────────
            $table->foreignId('employee_id')
                ->constrained()->cascadeOnDelete();
            $table->foreignId('leave_type_id')
                ->constrained()->restrictOnDelete();

            // ── Dates ───────────────────────────────────────────────
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('total_days');                   // Calculated days
            $table->boolean('half_day')->default(false);
            $table->enum('half_day_type', ['Morning', 'Afternoon'])->nullable();

            // ── Details ─────────────────────────────────────────────
            $table->text('reason');
            $table->string('document_path')->nullable();     // Supporting document

            // ── Status & Approval ────────────────────────────────────
            $table->enum('status', [
                'Pending',
                'Approved',
                'Rejected',
                'Cancelled',
                'Revoked',
            ])->default('Pending');

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();

            // ── Cancellation ─────────────────────────────────────────
            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ─────────────────────────────────────────────
            $table->index(['employee_id', 'status']);
            $table->index(['from_date', 'to_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
