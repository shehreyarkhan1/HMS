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
        Schema::create('patient_discharges', function (Blueprint $table) {
              $table->id();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->constrained('beds')->cascadeOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();          // discharging doctor
            $table->foreignId('processed_by')->constrained('users')->cascadeOnDelete();         // nurse/receptionist

            $table->string('discharge_number')->unique();   // DC-00001

            // ── Discharge Info ──
            $table->date('admitted_date');
            $table->date('discharge_date');
            $table->integer('total_days');                  // auto calculated

            $table->enum('discharge_type', [
                'Normal',           // recovered
                'LAMA',             // Left Against Medical Advice
                'Referred',         // transferred to another hospital
                'Expired',          // death
                'Absconded',        // ran away
            ])->default('Normal');

            $table->enum('condition_at_discharge', [
                'Recovered',
                'Improved',
                'Same',
                'Deteriorated',
                'Expired',
            ])->default('Improved');

            // ── Clinical Summary ──
            $table->text('admission_diagnosis');
            $table->text('final_diagnosis');
            $table->text('treatment_summary');
            $table->text('procedures_done')->nullable();
            $table->text('discharge_instructions');
            $table->text('medications_on_discharge')->nullable();   // discharge medicines list
            $table->text('diet_instructions')->nullable();
            $table->text('activity_instructions')->nullable();
            $table->date('follow_up_date')->nullable();
            $table->string('follow_up_with')->nullable();           // doctor name
            $table->text('notes')->nullable();

            // ── Status ──
            $table->enum('status', ['Draft', 'Final', 'Printed'])->default('Draft');
            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            $table->index('patient_id');
            $table->index('doctor_id');
            $table->index('discharge_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_discharges');
    }
};
