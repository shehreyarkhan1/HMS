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
        Schema::create('patient_nursing_notes', function (Blueprint $table) {
             $table->id();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('nurse_id')->constrained('users')->cascadeOnDelete();

            $table->enum('shift', ['Morning', 'Afternoon', 'Evening', 'Night']);
            $table->enum('note_type', [
                'General',
                'Medication Given',
                'Procedure Done',
                'Patient Complaint',
                'Family Communication',
                'Incident Report',
                'Handover Note',
            ])->default('General');

            $table->text('note');                           // main note content
            $table->text('interventions')->nullable();      // what was done
            $table->text('patient_response')->nullable();   // how patient responded
            $table->boolean('requires_doctor_attention')->default(false);
            $table->boolean('is_urgent')->default(false);

            $table->timestamp('noted_at');
            $table->timestamps();

            $table->index(['patient_id', 'noted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_nursing_notes');
    }
};
