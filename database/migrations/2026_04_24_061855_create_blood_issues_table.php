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
        Schema::create('blood_issues', function (Blueprint $table) {
            $table->id();

            $table->string('issue_id')->unique();               // BIS-00001

            $table->foreignId('blood_request_id')
                ->constrained('blood_requests')
                ->cascadeOnDelete();

            $table->foreignId('blood_donation_id')
                ->constrained('blood_donations')
                ->cascadeOnDelete();

            $table->foreignId('patient_id')
                ->constrained('patients')
                ->cascadeOnDelete();

            // ── ISSUE DETAILS ────────────────────────────────────────
            $table->string('blood_group');
            $table->string('bag_number')->nullable();
            $table->decimal('volume_ml', 6, 1)->nullable();
            $table->enum('component', [
                'Whole Blood',
                'Packed RBC',
                'Platelets',
                'Fresh Frozen Plasma',
                'Cryoprecipitate',
            ]);

            // ── TRANSFUSION ──────────────────────────────────────────
            $table->timestamp('issued_at');
            $table->timestamp('transfusion_started_at')->nullable();
            $table->timestamp('transfusion_completed_at')->nullable();

            // ── REACTION ─────────────────────────────────────────────
            $table->boolean('reaction_observed')->default(false);
            $table->enum('reaction_type', [
                'None',
                'Febrile',
                'Allergic',
                'Haemolytic',
                'TACO',              // Transfusion-associated circulatory overload
                'TRALI',             // Acute lung injury
                'Other',
            ])->default('None');
            $table->text('reaction_notes')->nullable();

            // ── ISSUED BY ─────────────────────────────────────────────
            $table->foreignId('issued_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['patient_id', 'issued_at']);
            $table->index('blood_group');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blood_issues');
    }
};
