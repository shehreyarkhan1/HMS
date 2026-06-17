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
        Schema::create('patient_visit_notes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();

            // ── SOAP Format (industry standard) ──
            $table->text('subjective')->nullable();     // S — Patient complaints, history
            $table->text('objective')->nullable();      // O — Examination findings
            $table->text('assessment')->nullable();     // A — Diagnosis / impression
            $table->text('plan')->nullable();           // P — Treatment plan

            // ── Additional ──
            $table->text('examination_findings')->nullable();   // physical exam
            $table->json('diagnosis_codes')->nullable();        // ICD codes array
            $table->text('follow_up_instructions')->nullable();
            $table->boolean('is_discharge_ready')->default(false);

            $table->enum('visit_type', [
                'Morning Round',
                'Evening Round',
                'Emergency Visit',
                'Consultation',
                'Post-Op Review',
                'Follow-up',
            ])->default('Morning Round');

            $table->timestamp('visited_at');
            $table->timestamps();

            $table->index(['patient_id', 'visited_at']);
            $table->index('doctor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_visit_notes');
    }
};
