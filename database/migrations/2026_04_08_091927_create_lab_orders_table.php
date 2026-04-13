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
        Schema::create('lab_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();        // LAB-00001

            // ── Relations ──
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();

            // ── NEW: Link to appointment ──
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->dateTime('order_date');

            // ── NEW: Priority ──
            $table->enum('priority', ['Routine', 'Urgent', 'STAT'])->default('Routine');
            // STAT = immediate (emergency), Urgent = 2-4 hrs, Routine = normal

            $table->enum('status', [
                'Pending',
                'Sample Collected',
                'Processing',
                'Completed',
                'Cancelled',
            ])->default('Pending');

            // ── NEW: Billing ──
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid'])->default('Unpaid');

            // ── NEW: Report delivery ──
            $table->boolean('report_delivered')->default(false);
            $table->timestamp('report_delivered_at')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index('order_date');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lab_orders');
    }
};
