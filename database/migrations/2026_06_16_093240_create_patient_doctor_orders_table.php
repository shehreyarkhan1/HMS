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
        Schema::create('patient_doctor_orders', function (Blueprint $table) {
              $table->id();

            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('bed_id')->nullable()->constrained('beds')->nullOnDelete();
            $table->foreignId('doctor_id')->constrained('doctors')->cascadeOnDelete();
            $table->foreignId('acknowledged_by')->nullable()->constrained('users')->nullOnDelete(); // nurse

            $table->string('order_number')->unique();       // ORD-00001

            $table->enum('order_type', [
                'Medication',       // drug order
                'Investigation',    // lab/radiology
                'Diet',             // diet order
                'Activity',         // bed rest / mobilize
                'Procedure',        // IV line, catheter etc
                'Monitoring',       // vitals every 4hr etc
                'Consult',          // refer to another doctor
                'Discharge',        // discharge order
                'Other',
            ]);

            $table->string('title');                        // short title
            $table->text('details');                        // full order details
            $table->text('special_instructions')->nullable();

            $table->enum('priority', ['Routine', 'Urgent', 'STAT'])->default('Routine');

            $table->enum('status', [
                'Pending',          // just written
                'Acknowledged',     // nurse saw it
                'In Progress',      // being done
                'Completed',        // done
                'Cancelled',        // cancelled
            ])->default('Pending');

            $table->timestamp('ordered_at');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();

            $table->index(['patient_id', 'status']);
            $table->index(['doctor_id', 'ordered_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_doctor_orders');
    }
};
