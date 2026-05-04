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
        Schema::create('blood_donors', function (Blueprint $table) {
            $table->id();

            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('donor_id')->unique();               // DNR-00001
            $table->enum('donor_type', ['Voluntary', 'Replacement', 'Autologous', 'Directed'])
                ->default('Voluntary');

            // ── PERSONAL INFO ────────────────────────────────────────
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('blood_group');                      // A+, B-, O+ etc.
            $table->decimal('weight_kg', 5, 1)->nullable();
            $table->string('cnic')->nullable()->unique();

            // ── CONTACT ──────────────────────────────────────────────
            $table->string('phone');
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();

            // ── MEDICAL ELIGIBILITY ──────────────────────────────────
            $table->boolean('is_eligible')->default(true);
            $table->text('ineligibility_reason')->nullable();
            $table->date('eligible_from')->nullable();         // After deferral period

            // ── DONATION TRACKING ─────────────────────────────────────
            $table->date('last_donation_date')->nullable();
            $table->unsignedSmallInteger('total_donations')->default(0);
            $table->date('next_eligible_date')->nullable();    // 90 days after last donation

            // ── LINKED PATIENT (if replacement donor) ────────────────
            $table->foreignId('patient_id')
                ->nullable()
                ->constrained('patients')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('blood_group');
            $table->index('is_eligible');
            $table->index('donor_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_donors');
    }
};
