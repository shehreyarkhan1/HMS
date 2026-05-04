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
        Schema::create('blood_donations', function (Blueprint $table) {
            $table->id();

            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('donation_id')->unique();            // DON-00001

            $table->foreignId('donor_id')
                ->constrained('blood_donors')
                ->cascadeOnDelete();

            // ── DONATION DETAILS ─────────────────────────────────────
            $table->date('donation_date');
            $table->time('donation_time')->nullable();
            $table->string('blood_group');                      // Confirmed at time of donation
            $table->decimal('volume_ml', 6, 1)->default(450);  // Standard 450ml
            $table->string('bag_number')->unique()->nullable(); // Physical bag label

            // ── COMPONENT TYPE ────────────────────────────────────────
            $table->enum('component', [
                'Whole Blood',
                'Packed RBC',
                'Platelets',
                'Fresh Frozen Plasma',
                'Cryoprecipitate',
            ])->default('Whole Blood');

            // ── SCREENING ────────────────────────────────────────────
            $table->enum('screening_status', [
                'Pending',
                'Passed',
                'Failed',
                'Discarded',
            ])->default('Pending');

            $table->boolean('hiv_tested')->default(false);
            $table->boolean('hbsag_tested')->default(false);   // Hepatitis B
            $table->boolean('hcv_tested')->default(false);     // Hepatitis C
            $table->boolean('vdrl_tested')->default(false);    // Syphilis
            $table->boolean('malaria_tested')->default(false);
            $table->text('screening_notes')->nullable();

            // ── STATUS ──────────────────────────────────────────────
            $table->enum('status', [
                'Available',
                'Reserved',
                'Issued',
                'Expired',
                'Discarded',
            ])->default('Available');

            // ── EXPIRY ───────────────────────────────────────────────
            $table->date('expiry_date');                        // Whole blood: 35 days; Platelets: 5 days

            // ── COLLECTED BY ──────────────────────────────────────────
            $table->foreignId('collected_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('blood_group');
            $table->index('status');
            $table->index('expiry_date');
            $table->index('component');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donations');
    }
};
