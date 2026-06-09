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
        Schema::create('disciplinary_actions', function (Blueprint $table) {
             $table->id();

            $table->string('action_number')->unique();       // DA-00001

            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();

            // ── Incident ─────────────────────────────────────────────
            $table->date('incident_date');
            $table->enum('incident_type', [
                'Misconduct',
                'Insubordination',
                'Tardiness',
                'Absenteeism',
                'Negligence',
                'Harassment',
                'Fraud',
                'Violence',
                'Policy Violation',
                'Other',
            ]);
            $table->text('incident_description');

            // ── Action ───────────────────────────────────────────────
            $table->enum('action_type', [
                'Verbal Warning',
                'Written Warning',
                'Show Cause Notice',
                'Suspension',
                'Demotion',
                'Salary Deduction',
                'Termination',
                'Other',
            ]);
            $table->date('action_date');
            $table->text('action_details');

            // ── Suspension details ───────────────────────────────────
            $table->date('suspension_from')->nullable();
            $table->date('suspension_to')->nullable();
            $table->integer('suspension_days')->nullable();
            $table->boolean('suspension_paid')->default(false);

            // ── Salary deduction ─────────────────────────────────────
            $table->decimal('deduction_amount', 10, 2)->default(0);
            $table->string('deduction_month')->nullable();   // Which month payroll

            // ── Employee response ─────────────────────────────────────
            $table->text('employee_response')->nullable();
            $table->date('response_deadline')->nullable();
            $table->boolean('response_received')->default(false);
            $table->date('response_received_date')->nullable();

            // ── Status ───────────────────────────────────────────────
            $table->enum('status', [
                'Issued',
                'Acknowledged',
                'Under Review',
                'Resolved',
                'Escalated',
                'Closed',
            ])->default('Issued');

            $table->boolean('is_appealed')->default(false);
            $table->text('appeal_details')->nullable();
            $table->enum('appeal_outcome', [
                'Upheld',
                'Overturned',
                'Modified',
            ])->nullable();

            // ── Issued by ────────────────────────────────────────────
            $table->foreignId('issued_by')
                ->constrained('employees')
                ->restrictOnDelete();
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('employees')
                ->nullOnDelete();

            $table->text('notes')->nullable();
            $table->string('document_path')->nullable();     // Show cause letter etc

            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
            $table->index('incident_date');
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinary_actions');
    }
};
