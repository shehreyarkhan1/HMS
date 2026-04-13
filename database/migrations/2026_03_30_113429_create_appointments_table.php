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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();

            // ── Core Relations ──
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained()->nullOnDelete();

            // ── Scheduling ──
            $table->date('appointment_date');
            $table->time('appointment_time')->nullable();
            $table->unsignedSmallInteger('duration_minutes')->default(15); // slot length

            // ── Token (OPD serial number per day per doctor) ──
            $table->unsignedSmallInteger('token_number')->nullable();

            // ── Type & Status ──
            $table->enum('type', ['OPD', 'IPD', 'Follow-up', 'Emergency'])->default('OPD');
            $table->enum('status', [
                'Scheduled',
                'Confirmed',
                'In-Progress',
                'Completed',
                'Cancelled',
                'No-show',
            ])->default('Scheduled');

            // ── Visit Details ──
            $table->string('reason')->nullable();           // Chief complaint
            $table->text('notes')->nullable();              // Doctor / reception notes
            $table->timestamp('consulted_at')->nullable();  // Actual consultation start

            // ── Follow-up ──
            $table->date('follow_up_date')->nullable();

            // ── Cancellation Audit ──
            $table->enum('cancelled_by', ['Patient', 'Doctor', 'Admin'])->nullable();
            $table->string('cancellation_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ──
            $table->index(['appointment_date', 'doctor_id']);
            $table->index(['patient_id', 'appointment_date']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
