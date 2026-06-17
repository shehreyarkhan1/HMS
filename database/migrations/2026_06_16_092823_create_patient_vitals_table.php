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
        Schema::create('patient_vitals', function (Blueprint $table) {
              $table->id();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('recorded_by')->constrained('users')->cascadeOnDelete(); // nurse/doctor

            // ── Core Vitals ──
            $table->decimal('temperature', 5, 2)->nullable();          // °F e.g. 98.6
            $table->string('temperature_route')->nullable();            // Oral, Axillary, Rectal
            $table->integer('pulse_rate')->nullable();                  // bpm
            $table->string('pulse_rhythm')->nullable();                 // Regular, Irregular
            $table->integer('respiratory_rate')->nullable();            // breaths/min
            $table->integer('systolic_bp')->nullable();                 // mmHg
            $table->integer('diastolic_bp')->nullable();                // mmHg
            $table->string('bp_position')->nullable();                  // Sitting, Standing, Lying
            $table->decimal('oxygen_saturation', 5, 2)->nullable();    // % SpO2
            $table->string('oxygen_delivery')->nullable();              // Room Air, Mask, Nasal Cannula

            // ── Secondary Vitals ──
            $table->decimal('blood_glucose', 6, 2)->nullable();        // mg/dL
            $table->string('blood_glucose_timing')->nullable();         // Fasting, Post-meal, Random
            $table->decimal('weight', 6, 2)->nullable();               // kg
            $table->decimal('height', 5, 2)->nullable();               // cm
            $table->decimal('bmi', 5, 2)->nullable();                  // auto-calculated

            // ── Pain & Consciousness ──
            $table->integer('pain_score')->nullable();                  // 0-10
            $table->string('pain_location')->nullable();
            $table->integer('gcs_score')->nullable();                   // Glasgow Coma Scale 3-15
            $table->integer('gcs_eye')->nullable();                     // E 1-4
            $table->integer('gcs_verbal')->nullable();                  // V 1-5
            $table->integer('gcs_motor')->nullable();                   // M 1-6

            // ── ICU Specific ──
            $table->integer('central_venous_pressure')->nullable();     // mmHg (ICU)
            $table->decimal('urine_output', 8, 2)->nullable();         // ml/hr
            $table->decimal('fluid_intake', 8, 2)->nullable();         // ml
            $table->decimal('fluid_output', 8, 2)->nullable();         // ml

            // ── Meta ──
            $table->enum('shift', ['Morning', 'Afternoon', 'Evening', 'Night'])->default('Morning');
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');

            $table->timestamps();

            $table->index(['patient_id', 'recorded_at']);
            $table->index('bed_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_vitals');
    }
};
