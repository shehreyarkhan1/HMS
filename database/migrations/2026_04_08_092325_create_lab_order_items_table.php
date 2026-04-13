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
        Schema::create('lab_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lab_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lab_test_id')->constrained()->cascadeOnDelete();

            // ── NEW: Which sample used for this test ──
            $table->foreignId('lab_sample_id')
                ->nullable()
                ->constrained('lab_samples')
                ->nullOnDelete();

            // ── Pricing ──
            $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('final_price', 10, 2)->default(0); // price - discount

            $table->enum('status', [
                'Pending',
                'Processing',
                'Completed',
                'Cancelled',
            ])->default('Pending');

            // ── NEW: Who processed + when ──
            $table->string('technician_name')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['lab_order_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_order_items');
    }
};
