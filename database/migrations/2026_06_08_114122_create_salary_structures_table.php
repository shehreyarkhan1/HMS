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
        Schema::create('salary_structures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            // ── Basic ────────────────────────────────────────────────────────
            $table->decimal('basic_salary', 12, 2)->default(0);

            // ── Allowances ───────────────────────────────────────────────────
            $table->decimal('house_rent_allowance', 10, 2)->default(0);   // HRA — usually 45% of basic
            $table->decimal('medical_allowance', 10, 2)->default(0);
            $table->decimal('transport_allowance', 10, 2)->default(0);
            $table->decimal('meal_allowance', 10, 2)->default(0);
            $table->decimal('special_allowance', 10, 2)->default(0);
            $table->decimal('other_allowance', 10, 2)->default(0);
            $table->text('other_allowance_description')->nullable();

            // ── Computed Gross ───────────────────────────────────────────────
            // Basic + HRA + Medical + Transport + Meal + Special + Other
            $table->decimal('gross_salary', 12, 2)->default(0);

            // ── EOBI ─────────────────────────────────────────────────────────
            // Pakistan: Employee 1% of min wage, Employer 5% of min wage
            $table->boolean('eobi_applicable')->default(true);
            $table->decimal('eobi_employee_share', 10, 2)->default(0);    // ~370/month (employee ka hissa)
            $table->decimal('eobi_employer_share', 10, 2)->default(0);    // ~1850/month (hospital ka hissa — cost only, not deducted from salary)

            // ── Income Tax ───────────────────────────────────────────────────
            $table->boolean('is_tax_exempt')->default(false);
            $table->string('tax_slab')->nullable();                        // e.g. "0-600,000 = 0%"
            $table->decimal('income_tax_monthly', 10, 2)->default(0);     // Monthly calculated tax

            // ── Other Deductions ─────────────────────────────────────────────
            $table->decimal('provident_fund', 10, 2)->default(0);         // PF — if applicable
            $table->decimal('loan_deduction', 10, 2)->default(0);         // Salary advance recovery
            $table->decimal('other_deduction', 10, 2)->default(0);
            $table->text('other_deduction_description')->nullable();

            // ── Computed Totals ──────────────────────────────────────────────
            // eobi_employee_share + income_tax_monthly + provident_fund + loan + other
            $table->decimal('total_deductions', 10, 2)->default(0);

            // gross_salary - total_deductions
            $table->decimal('net_salary', 12, 2)->default(0);

            // ── Effective Period ─────────────────────────────────────────────
            $table->date('effective_from');
            $table->date('effective_to')->nullable();                      // null = still current
            $table->boolean('is_current')->default(true);                  // Only one can be current per employee

            $table->text('notes')->nullable();
            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            // ── Indexes ──────────────────────────────────────────────────────
            $table->index(['employee_id', 'is_current']);
            $table->index(['employee_id', 'effective_from']);
            $table->index('effective_from');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_structures');
    }
};
