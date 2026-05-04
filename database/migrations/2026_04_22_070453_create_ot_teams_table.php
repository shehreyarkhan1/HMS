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
        Schema::create('ot_teams', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ot_schedule_id')
                ->constrained('ot_schedules')
                ->cascadeOnDelete();

            // ── MEMBER TYPE ──────────────────────────────────────────
            $table->enum('role', [
                'Assistant Surgeon',
                'Scrub Nurse',
                'Circulating Nurse',
                'OT Technician',
                'Anesthesia Technician',
                'Perfusionist',
                'Observer',
                'Other',
            ]);

            // ── MEMBER IDENTITY ──────────────────────────────────────
            // Could be doctor or employee
            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->string('notes')->nullable();
            $table->timestamps();

            $table->index('ot_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_teams');
    }
};
