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
        Schema::create('lab_samples', function (Blueprint $table) {
            $table->id();
            $table->string('sample_number')->unique();       // SMP-00001

            $table->foreignId('lab_order_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('sample_type_id')
                ->constrained('lab_sample_types')
                ->restrictOnDelete();

            $table->enum('status', [
                'Pending',
                'Collected',
                'Received',
                'In Process',
                'Completed',
                'Rejected',
            ])->default('Pending');

            // ── Timestamps for each stage ──
            $table->dateTime('collected_at')->nullable();
            $table->dateTime('received_at')->nullable();
            $table->dateTime('processed_at')->nullable();

            // ── NEW: Who collected ──
            $table->string('collected_by')->nullable();

            // ── NEW: Rejection reason ──
            $table->string('rejection_reason')->nullable();  // Hemolyzed, Clotted, etc

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['lab_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_samples');
    }
};
