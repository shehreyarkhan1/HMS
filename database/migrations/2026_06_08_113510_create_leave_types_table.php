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
        Schema::create('leave_types', function (Blueprint $table) {
            $table->id();

            $table->string('name');                          // Casual Leave, Annual Leave
            $table->string('code')->unique();                // CL, AL, SL, ML
            $table->text('description')->nullable();

            // ── Entitlement ─────────────────────────────────────────
            $table->integer('days_per_year')->default(0);    // Max days allowed per year
            $table->boolean('is_paid')->default(true);       // Paid or unpaid
            $table->boolean('carry_forward')->default(false); // Carry to next year
            $table->integer('max_carry_forward')->default(0); // Max days to carry
            $table->boolean('encashable')->default(false);   // Can be encashed

            // ── Rules ───────────────────────────────────────────────
            $table->integer('min_service_days')->default(0); // Min service before applying
            $table->integer('max_consecutive_days')->nullable(); // Max at a time
            $table->integer('notice_days_required')->default(0); // Advance notice
            $table->boolean('requires_document')->default(false); // Document required
            $table->string('document_description')->nullable();  // What document

            // ── Applicable to ───────────────────────────────────────
            $table->boolean('applicable_male')->default(true);
            $table->boolean('applicable_female')->default(true);
            $table->json('applicable_employment_types')->nullable(); // Permanent, Contract etc

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_types');
    }
};
