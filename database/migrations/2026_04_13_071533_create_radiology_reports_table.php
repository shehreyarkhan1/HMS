<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('radiology_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('radiology_order_item_id')
                ->constrained()
                ->cascadeOnDelete();

            // ── Report content ──
            $table->text('findings')->nullable();            // Detailed radiologist findings
            $table->text('impression')->nullable();          // Final conclusion / diagnosis
            $table->text('recommendations')->nullable();     // Follow-up, further tests
            $table->string('comparison')->nullable();        // "Compared with prior CT dated..."

            // ── Critical / urgent findings ──
            $table->boolean('is_critical')->default(false);  // Critical finding flag
            $table->text('critical_notes')->nullable();      // What's critical
            $table->timestamp('critical_notified_at')->nullable(); // When doctor was informed
            $table->string('critical_notified_to')->nullable();    // Which doctor notified

            // ── Reporting workflow ──
            $table->enum('status', [
                'Draft',
                'Pending Verification',
                'Verified',
                'Amended',
            ])->default('Draft');

            // ── Reporting radiologist ──
            $table->foreignId('reported_by')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->timestamp('reported_at')->nullable();

            // ── Verification (senior radiologist) ──
            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->boolean('is_verified')->default(false);

            // ── Amendment tracking ──
            $table->text('amendment_reason')->nullable();
            $table->foreignId('amended_by')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->timestamp('amended_at')->nullable();

            $table->timestamps();

            $table->index('is_critical');
            $table->index('is_verified');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_reports');
    }
};
