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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->unique()
                ->constrained('employees')
                ->cascadeOnDelete();

            // ── DOCTOR ID ────────────────────────────────────────────
            $table->string('doctor_id')->unique(); // DOC-00001

            // ── CLINICAL INFORMATION ─────────────────────────────────
            $table->string('specialization');
            // Note: 'qualification' yahan rehne dein kyunki doctor ki medical degrees 
            // employee ki generic education se different aur detail mein hoti hain.
            $table->string('qualification');
            $table->string('pmdc_number')->nullable();
            $table->string('sub_department')->nullable();
            $table->enum('doctor_type', [
                'Consultant',
                'Medical Officer',
                'House Officer',
                'Visiting',
                'Specialist',
            ])->default('Medical Officer');

            // ── CONSULTATION ─────────────────────────────────────────
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->integer('avg_consultation_mins')->default(15);
            $table->enum('availability', [
                'Available',
                'In Consultation',
                'On Leave',
                'Off Duty',
            ])->default('Available');

            $table->json('available_days')->nullable(); // Maslan: ["Mon", "Wed", "Fri"]

            // ── NOTES & STATUS ───────────────────────────────────────
            $table->text('bio')->nullable();
            $table->text('clinical_notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('accepts_new_patients')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index('availability');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
