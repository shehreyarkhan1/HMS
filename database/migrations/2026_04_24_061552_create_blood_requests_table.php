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
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();

            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('request_id')->unique();             // BRQ-00001

            // ── RELATIONS ───────────────────────────────────────────
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            $table->foreignId('doctor_id')
                ->nullable()
                ->constrained('doctors')
                ->nullOnDelete();

            // ── BLOOD REQUIREMENT ─────────────────────────────────────
            $table->string('blood_group');
            $table->enum('component', [
                'Whole Blood',
                'Packed RBC',
                'Platelets',
                'Fresh Frozen Plasma',
                'Cryoprecipitate',
            ])->default('Whole Blood');

            $table->unsignedSmallInteger('units_required');
            $table->unsignedSmallInteger('units_approved')->default(0);

            // ── URGENCY ──────────────────────────────────────────────
            $table->enum('urgency', [
                'Routine',      // Within 24h
                'Urgent',       // Within 4h
                'Emergency',    // Immediate
            ])->default('Routine');

            // ── CLINICAL INFO ────────────────────────────────────────
            $table->string('indication');                       // Reason for transfusion
            $table->string('ward')->nullable();
            $table->string('bed_number')->nullable();
            $table->decimal('patient_hemoglobin', 4, 1)->nullable(); // Hb level

            // ── STATUS ──────────────────────────────────────────────
            $table->enum('status', [
                'Pending',
                'Under Review',
                'Crossmatch',   // Cross-matching in progress
                'Approved',
                'Partially Fulfilled',
                'Fulfilled',
                'Cancelled',
                'Rejected',
            ])->default('Pending');

            $table->string('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('fulfilled_at')->nullable();

            // ── PROCESSED BY ──────────────────────────────────────────
            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['patient_id', 'status']);
            $table->index('blood_group');
            $table->index('urgency');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
