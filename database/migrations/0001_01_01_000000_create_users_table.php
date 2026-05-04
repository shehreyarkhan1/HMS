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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // ── LINK TO EMPLOYEE ─────────────────────────────────────
            // NULL = system user (super admin) jo employee nahi hai
            // NOT NULL = employee jise system access mila hai
            $table->foreignId('employee_id')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            // ── LOGIN CREDENTIALS ────────────────────────────────────
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // ── ROLE ─────────────────────────────────────────────────
            $table->enum('role', [
                'super_admin',
                'receptionist',
                'doctor',
                'nurse',
                'lab_technician',
                'radiologist',
                'pharmacist',
                'hr_manager',
                'accountant',
            ])->default('receptionist');

            // ── STATUS ───────────────────────────────────────────────
            $table->boolean('is_active')->default(true);

            // ── STANDARD LARAVEL ─────────────────────────────────────
            $table->rememberToken();
            $table->timestamps();
        });

        // ── PASSWORD RESETS ──────────────────────────────────────────
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // ── SESSIONS ─────────────────────────────────────────────────
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
