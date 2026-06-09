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
        Schema::create('payslips', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payroll_run_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            $table->string('payslip_number')->unique();              // PS-2025-06-001

            // ── Snapshot — salary_structure se copy at time of payroll ───────
            // Freeze hota hai — baad mein salary change ho toh bhi yeh same rahega
            $table->foreignId('salary_structure_id')
                ->nullable()
                ->constrained('salary_structures')
                ->nullOnDelete();

            // ── Attendance Summary ────────────────────────────────────────────
            $table->integer('total_working_days')->default(0);       // Month mein total working days
            $table->integer('present_days')->default(0);
            $table->integer('absent_days')->default(0);
            $table->integer('late_days')->default(0);                // Late arrivals
            $table->integer('half_days')->default(0);
            $table->integer('leave_days')->default(0);               // Approved leaves
            $table->integer('holiday_days')->default(0);
            $table->decimal('overtime_hours', 5, 2)->default(0);

            // ── Earnings (Frozen snapshot from salary_structure) ─────────────
            $table->decimal('basic_salary', 12, 2)->default(0);
            $table->decimal('house_rent_allowance', 10, 2)->default(0);
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('meal_allowance', 10, 2)->default(0);
            $table->decimal('special_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);   // Overtime pay
            $table->decimal('bonus', 10, 2)->default(0);             // One-time bonus
            $table->decimal('arrears', 10, 2)->default(0);           // Back pay agar koi tha
            $table->decimal('gross_salary', 12, 2)->default(0);      // Sum of all earnings

            // ── Deductions (Frozen snapshot) ──────────────────────────────────
            $table->decimal('income_tax_monthly', 10, 2)->default(0);   // Renamed — clarity
            $table->string('tax_slab')->nullable();                      // Which slab applied
            $table->decimal('eobi_employee_share', 10, 2)->default(0);  // Employee ka EOBI cut
            $table->decimal('provident_fund', 10, 2)->default(0);
            $table->decimal('loan_deduction', 10, 2)->default(0);
            $table->decimal('absent_deduction', 10, 2)->default(0);     // Per day deduction * absent days
            $table->decimal('late_deduction', 10, 2)->default(0);       // Late fine if applicable
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->text('other_deduction_description')->nullable();
            $table->decimal('total_deductions', 10, 2)->default(0);     // Sum of all deductions

            // ── Net ───────────────────────────────────────────────────────────
            $table->decimal('net_salary', 12, 2)->default(0);           // gross - total_deductions

            // ── Per Day Rate (for absent deduction calculation) ───────────────
            $table->decimal('per_day_salary', 10, 2)->default(0);       // gross / working_days

            // ── Payment ───────────────────────────────────────────────────────
            $table->enum('payment_method', [
                'Bank Transfer',
                'Cash',
                'Cheque',
            ])->default('Bank Transfer');
            $table->string('bank_account_number')->nullable();           // Snapshot from employee
            $table->string('bank_name')->nullable();                     // Snapshot from employee
            $table->boolean('is_paid')->default(false);
            $table->date('paid_on')->nullable();
            $table->string('transaction_reference')->nullable();         // Bank transfer ref number

            // ── Status ────────────────────────────────────────────────────────
            $table->enum('status', [
                'Draft',
                'Generated',
                'Approved',
                'Paid',
                'Cancelled',
            ])->default('Draft');

            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ───────────────────────────────────────────────────────
            $table->unique(['payroll_run_id', 'employee_id']);           // Ek employee ek payroll mein ek baar
            $table->index(['employee_id', 'status']);
            $table->index(['employee_id', 'is_paid']);
            $table->index('payslip_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payslips');
    }
};
