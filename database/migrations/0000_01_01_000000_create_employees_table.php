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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // ── IDENTIFICATION ──────────────────────────────────────
            $table->string('employee_id')->unique();          // EMP-00001
            $table->string('badge_number')->unique()->nullable();
            // ── PERSONAL INFORMATION ────────────────────────────────
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->enum('marital_status', [
                'Single',
                'Married',
                'Divorced',
                'Widowed'
            ])->default('Single');
            $table->string('religion')->nullable();
            $table->string('nationality')->default('Pakistani');
            $table->string('cnic')->unique()->nullable();
            $table->date('cnic_expiry')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('photo')->nullable();
            // ── CONTACT INFORMATION ─────────────────────────────────
            $table->string('personal_phone');
            $table->string('office_phone')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('office_email')->unique()->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            // ── ADDRESS ─────────────────────────────────────────────
            $table->text('present_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();
            // ── EMPLOYMENT DETAILS ──────────────────────────────────
            $table->string('department');
            $table->string('designation');
            $table->string('job_grade')->nullable();
            $table->enum('employment_type', [
                'Permanent',
                'Contractual',
                'Probationary',
                'Part-Time',
                'Intern',
                'Daily-Wage',
            ])->default('Permanent');
            $table->enum('employment_status', [
                'Active',
                'On Leave',
                'Suspended',
                'Terminated',
                'Resigned',
                'Retired',
            ])->default('Active');
            $table->date('joining_date');
            $table->date('confirmation_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->date('resignation_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->text('termination_reason')->nullable();
            // ── SHIFT & SCHEDULE ────────────────────────────────────
            $table->enum('shift', [
                'Morning',
                'Evening',
                'Night',
                'Rotating',
                'Custom'
            ])->default('Morning');
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->integer('weekly_hours')->default(48);
            $table->json('working_days')->nullable();
            // ── EDUCATION ───────────────────────────────────────────
            $table->string('highest_qualification')->nullable();
            $table->string('specialization')->nullable();
            $table->string('institution')->nullable();
            $table->year('graduation_year')->nullable();
            // ── EXPERIENCE ──────────────────────────────────────────
            $table->integer('total_experience_years')->default(0);
            $table->string('previous_employer')->nullable();
            $table->string('previous_designation')->nullable();
            // ── BANK & SALARY ───────────────────────────────────────
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('iban')->nullable();
            $table->enum('salary_type', [
                'Monthly',
                'Daily',
                'Hourly'
            ])->default('Monthly');
            $table->decimal('basic_salary', 12, 2)->default(0);
            // ── GOVERNMENT / COMPLIANCE ─────────────────────────────
            $table->string('ntn_number')->nullable();
            $table->string('eobi_number')->nullable();
            $table->string('socso_number')->nullable();
            $table->boolean('is_tax_filer')->default(false);

            // ── SYSTEM ACCESS FLAG ───────────────────────────────────
            // user_id yahan nahi hoga — users table mein employee_id hoga
            // Yeh sirf ek quick flag hai HR ke liye
            $table->boolean('has_system_access')->default(false);

            // ── NOTES ───────────────────────────────────────────────
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // ── INDEXES ─────────────────────────────────────────────
            $table->index('department');
            $table->index('employment_status');
            $table->index('employment_type');
            $table->index('joining_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
