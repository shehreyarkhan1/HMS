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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mrn')->unique();           // Medical Record Number: MRN-00001
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('blood_group')->nullable();  // A+, B-, O+ etc
            $table->string('phone');
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('cnic')->nullable()->unique(); // 13 digit
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->enum('patient_type', ['OPD', 'IPD', 'Emergency'])->default('OPD');
            $table->enum('status', ['Active', 'Admitted', 'Discharged', 'Deceased'])->default('Active');
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
