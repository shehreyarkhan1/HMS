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
        Schema::create('body_release_records', function (Blueprint $table) {
            $table->id();
            $table->string('release_id')->unique(); // BRL-00001

            $table->foreignId('mortuary_record_id')
                ->constrained('mortuary_records')
                ->cascadeOnDelete();

            // ── RELEASED TO ───────────────────────────────────────────────
            $table->string('released_to_name');
            $table->string('released_to_cnic');
            $table->string('released_to_relation');
            $table->string('released_to_phone');
            $table->text('released_to_address')->nullable();

            // ── WITNESSES ─────────────────────────────────────────────────
            $table->string('witness_1_name')->nullable();
            $table->string('witness_1_cnic')->nullable();
            $table->string('witness_2_name')->nullable();
            $table->string('witness_2_cnic')->nullable();

            // ── RELEASE INFO ──────────────────────────────────────────────
            $table->timestamp('released_at');
            $table->foreignId('released_by')
                ->constrained('employees')
                ->cascadeOnDelete();

            $table->enum('transport_type', [
                'Hospital Ambulance', 'Private Ambulance',
                'Private Vehicle', 'On Foot', 'Other',
            ])->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('destination');              // Qabrastan ya ghar

            // ── DEATH CERTIFICATE CHECK ───────────────────────────────────
            $table->boolean('death_certificate_provided')->default(false);
            $table->string('death_certificate_number')->nullable();

            // ── BELONGINGS ────────────────────────────────────────────────
            $table->boolean('belongings_returned')->default(false);
            $table->text('belongings_list')->nullable();
            $table->decimal('valuables_amount', 10, 2)->default(0); // Cash jo patient ke paas tha
            $table->boolean('valuables_returned')->default(false);

            // ── POLICE CLEARANCE (MLC cases) ─────────────────────────────
            $table->boolean('police_clearance_obtained')->default(false);
            $table->string('police_clearance_number')->nullable();

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('released_at');
            $table->index('mortuary_record_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_release_records');
    }
};
