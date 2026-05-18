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
        Schema::create('mortuary_records', function (Blueprint $table) {
            $table->id();
            $table->string('mortuary_id')->unique(); // MTY-00001

            // ── PATIENT LINK ─────────────────────────────────────────────
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // ── DEATH INFORMATION ────────────────────────────────────────
            $table->timestamp('death_datetime');
            $table->enum('death_location', [
                'Ward', 'ICU', 'CCU', 'Emergency', 'OT', 'DOA', 'Outside Hospital',
            ])->default('Ward');

            $table->string('ward')->nullable();
            $table->string('bed_number')->nullable();

            // ── CAUSE OF DEATH (ICD-10 standard) ─────────────────────────
            $table->string('immediate_cause');              // Part I (a)
            $table->string('intermediate_cause')->nullable(); // Part I (b)
            $table->string('underlying_cause')->nullable();   // Part I (c)
            $table->string('contributing_cause')->nullable(); // Part II

            $table->enum('manner_of_death', [
                'Natural', 'Accidental', 'Homicidal', 'Suicidal', 'Undetermined',
            ])->default('Natural');

            // ── DECLARING DOCTOR ─────────────────────────────────────────
            $table->foreignId('declared_by')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->timestamp('declared_at')->nullable();

            // ── BODY MANAGEMENT ──────────────────────────────────────────
            $table->string('locker_number')->nullable();
            $table->enum('body_condition', [
                'Normal', 'Decomposed', 'Burned', 'Traumatic', 'Other',
            ])->nullable();
            $table->decimal('body_weight_kg', 5, 1)->nullable();
            $table->text('identification_marks')->nullable(); // Pehchaan ke liye

            // ── STATUS ───────────────────────────────────────────────────
            $table->enum('status', [
                'Admitted',
                'Postmortem Pending',
                'Postmortem Done',
                'Certificate Issued',
                'Released',
                'Transferred',
                'Unclaimed',        // Koi waris nahi aaya
            ])->default('Admitted');

            // ── POSTMORTEM ───────────────────────────────────────────────
            $table->boolean('postmortem_required')->default(false);
            $table->enum('postmortem_ordered_by', [
                'Doctor', 'Police', 'Court', 'Hospital',
            ])->nullable();
            $table->enum('postmortem_status', [
                'Not Required', 'Pending', 'In Progress', 'Completed',
            ])->default('Not Required');
            $table->timestamp('postmortem_started_at')->nullable();
            $table->timestamp('postmortem_completed_at')->nullable();
            $table->foreignId('postmortem_by')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();
            $table->text('postmortem_findings')->nullable();
            $table->string('postmortem_report_number')->nullable();

            // ── MEDICO LEGAL CASE (MLC) ───────────────────────────────────
            $table->boolean('is_medico_legal')->default(false);
            $table->string('mlc_number')->nullable();
            $table->string('police_station')->nullable();
            $table->string('investigating_officer')->nullable();
            $table->string('fir_number')->nullable();
            $table->timestamp('police_informed_at')->nullable();

            // ── NEXT OF KIN (Waris) ───────────────────────────────────────
            $table->string('nok_name')->nullable();         // Next of Kin
            $table->string('nok_relation')->nullable();
            $table->string('nok_cnic')->nullable();
            $table->string('nok_phone')->nullable();
            $table->boolean('nok_informed')->default(false);
            $table->timestamp('nok_informed_at')->nullable();

            // ── PROCESSED BY ─────────────────────────────────────────────
            $table->foreignId('admitted_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // ── INDEXES ───────────────────────────────────────────────────
            $table->index('status');
            $table->index('death_datetime');
            $table->index('is_medico_legal');
            $table->index(['patient_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mortuary_records');
    }
};
