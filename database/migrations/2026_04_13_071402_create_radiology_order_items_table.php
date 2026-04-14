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
        Schema::create('radiology_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('radiology_exam_id')
                ->constrained()
                ->restrictOnDelete();

            // ── Pricing at time of order ──
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0);

            $table->enum('status', [
                'Pending',
                'Scheduled',
                'In Progress',
                'Scan Completed',
                'Reported',
                'Cancelled',
            ])->default('Pending');

            // ── Scan details ──
            $table->dateTime('scanned_at')->nullable();
            $table->string('technician_name')->nullable();  // Who performed the scan
            $table->string('equipment_used')->nullable();   // Machine / room used

            // ── Contrast tracking ──
            $table->boolean('contrast_used')->default(false);
            $table->string('contrast_agent')->nullable();
            $table->decimal('contrast_dose_ml', 8, 2)->nullable();
            $table->boolean('contrast_reaction')->default(false);
            $table->text('contrast_reaction_notes')->nullable();

            $table->timestamps();

            $table->index(['radiology_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_order_items');
    }
};
