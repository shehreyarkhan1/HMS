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
        Schema::create('death_certificates', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_number')->unique(); // DC-2026-00001

            $table->foreignId('mortuary_record_id')
                ->constrained('mortuary_records')
                ->cascadeOnDelete();

            // ── CERTIFICATE DETAILS ───────────────────────────────────────
            $table->enum('certificate_type', [
                'Hospital Death Certificate',   // Normal hospital mein death
                'Medico Legal Certificate',     // MLC case
                'Stillbirth Certificate',       // Stillbirth
                'Duplicate',                    // Duplicate copy
            ])->default('Hospital Death Certificate');

            $table->enum('purpose', [
                'Burial / Funeral',
                'NADRA Registration',
                'Legal Proceedings',
                'Insurance Claim',
                'Embassy / Visa',
                'Other',
            ])->default('Burial / Funeral');

            // ── ISSUED TO (Next of Kin) ───────────────────────────────────
            $table->string('issued_to_name');
            $table->string('issued_to_cnic')->nullable();
            $table->string('issued_to_relation');
            $table->string('issued_to_phone')->nullable();
            $table->string('issued_to_address')->nullable();

            // ── SIGNATORY ─────────────────────────────────────────────────
            $table->foreignId('signed_by_doctor')       // Certificate sign karne wala doctor
                ->constrained('doctors')
                ->cascadeOnDelete();

            $table->foreignId('verified_by')            // Medical Director / Admin
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->foreignId('issued_by')              // Counter staff
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            // ── ISSUANCE ──────────────────────────────────────────────────
            $table->timestamp('issued_at');
            $table->integer('copy_number')->default(1); // 1st copy, 2nd copy...
            $table->integer('total_copies')->default(1);

            // ── VERIFICATION ──────────────────────────────────────────────
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            // ── FEES ──────────────────────────────────────────────────────
            $table->decimal('fee_charged', 8, 2)->default(0);
            $table->boolean('fee_paid')->default(false);
            $table->foreignId('bill_id')                // Billing se link
                ->nullable()
                ->constrained('bills')
                ->nullOnDelete();

            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('certificate_number');
            $table->index('issued_at');
            $table->index(['mortuary_record_id', 'copy_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('death_certificates');
    }
};
