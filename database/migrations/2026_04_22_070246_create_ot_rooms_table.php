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
        Schema::create('ot_rooms', function (Blueprint $table) {
            $table->id();

            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('room_code')->unique();          // OT-01, OT-02
            $table->string('name');                         // Main OT, Cardiac OT

            // ── CLASSIFICATION ──────────────────────────────────────
            $table->enum('room_type', [
                'General',
                'Cardiac',
                'Neurology',
                'Orthopedic',
                'Gynecology',
                'ENT',
                'Eye',
                'Trauma',
                'Emergency',
            ])->default('General');

            // ── STATUS ──────────────────────────────────────────────
            $table->enum('status', [
                'Available',
                'Occupied',
                'Cleaning',
                'Maintenance',
                'Out of Service',
            ])->default('Available');

            // ── FACILITIES ──────────────────────────────────────────
            $table->boolean('has_anesthesia_machine')->default(true);
            $table->boolean('has_ventilator')->default(false);
            $table->boolean('has_laparoscopy')->default(false);
            $table->boolean('has_c_arm')->default(false);       // X-Ray C-Arm
            $table->boolean('is_laminar_flow')->default(false); // Sterile air flow
            $table->text('equipment_notes')->nullable();

            // ── LOCATION ────────────────────────────────────────────
            $table->string('floor')->nullable();
            $table->string('block')->nullable();

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('room_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ot_rooms');
    }
};
