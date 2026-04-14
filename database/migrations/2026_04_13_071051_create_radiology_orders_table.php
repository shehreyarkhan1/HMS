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
        Schema::create('radiology_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();       // RAD-00001

            // ── Relations ──
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->dateTime('order_date');
            $table->dateTime('scheduled_at')->nullable();   // When scan is booked

            // ── Clinical info from referring doctor ──
            $table->text('clinical_history')->nullable();   // Patient symptoms, diagnosis
            $table->text('clinical_indication')->nullable(); // Reason for ordering

            // ── Priority ──
            $table->enum('priority', ['Routine', 'Urgent', 'STAT'])->default('Routine');

            // ── Order status lifecycle ──
            $table->enum('status', [
                'Pending',
                'Scheduled',
                'In Progress',
                'Scan Completed',
                'Reporting',
                'Reported',
                'Verified',
                'Delivered',
                'Cancelled',
            ])->default('Pending');

            // ── Billing ──
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('net_amount', 10, 2)->default(0);
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid'])->default('Unpaid');

            // ── Report delivery ──
            $table->boolean('report_delivered')->default(false);
            $table->timestamp('report_delivered_at')->nullable();
            $table->string('delivered_to')->nullable();     // Patient name / relative

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index('order_date');
            $table->index('priority');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_orders');
    }
};
