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
        Schema::create('ot_schedules', function (Blueprint $table) {
            $table->id();

            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('surgery_id')->unique();         // SRG-00001

            // ── RELATIONS ───────────────────────────────────────────
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('ot_room_id')
                ->nullable()
                ->constrained('ot_rooms')
                ->nullOnDelete();

            // ── PRIMARY SURGEON ──────────────────────────────────────
            $table->foreignId('surgeon_id')
                ->constrained('doctors')
                ->cascadeOnDelete();

            // ── ANESTHESIOLOGIST ─────────────────────────────────────
            $table->foreignId('anesthesiologist_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            // ── SCHEDULING ──────────────────────────────────────────
            $table->date('scheduled_date');
            $table->time('scheduled_time');
            $table->unsignedSmallInteger('estimated_duration_mins')->default(60);

            // ── ACTUAL TIMES (filled during/after surgery) ───────────
            $table->timestamp('actual_start_time')->nullable();
            $table->timestamp('actual_end_time')->nullable();

            // ── SURGERY CLASSIFICATION ───────────────────────────────
            $table->enum('surgery_type', [
                'Elective',     // Planned, non-urgent
                'Urgent',       // Required within days
                'Emergency',    // Immediate, life-threatening
                'Diagnostic',   // Exploratory / biopsy
            ])->default('Elective');

            $table->enum('priority', [
                'Routine',
                'Priority',
                'Urgent',
                'Emergency',
            ])->default('Routine');

            // ── ANESTHESIA ───────────────────────────────────────────
            $table->enum('anesthesia_type', [
                'General',
                'Local',
                'Regional',
                'Spinal',
                'Epidural',
                'Sedation',
                'None',
            ])->nullable();

            // ── STATUS ──────────────────────────────────────────────
            $table->enum('status', [
                'Scheduled',
                'Confirmed',
                'Preparing',     // Patient being prepped
                'In-Progress',   // Surgery underway
                'Completed',
                'Postponed',
                'Cancelled',
            ])->default('Scheduled');

            // ── CLINICAL DETAILS ─────────────────────────────────────
            $table->string('diagnosis');                        // Pre-op diagnosis
            $table->string('procedure_name');                   // Surgery procedure name
            $table->text('procedure_details')->nullable();      // Detailed description
            $table->text('pre_op_instructions')->nullable();    // NPO, prep etc.
            $table->text('post_op_notes')->nullable();          // Post surgery notes
            $table->text('complications')->nullable();          // Any complications
            $table->string('post_op_destination')->nullable();  // ICU, Ward, Recovery

            // ── CONSENT & ADMIN ──────────────────────────────────────
            $table->boolean('consent_obtained')->default(false);
            $table->timestamp('consent_at')->nullable();
            $table->string('consent_by')->nullable();           // Name of who gave consent
            $table->boolean('pre_op_assessment_done')->default(false);
            $table->text('pre_op_assessment_notes')->nullable();

            // ── CANCELLATION ────────────────────────────────────────
            $table->string('postpone_reason')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->date('rescheduled_date')->nullable();

            // ── BOOKED BY ────────────────────────────────────────────
            $table->foreignId('booked_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // ── INDEXES ─────────────────────────────────────────────
            $table->index(['scheduled_date', 'ot_room_id']);
            $table->index(['patient_id', 'scheduled_date']);
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_schedules');
    }
};
