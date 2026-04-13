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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('doctor_id')->unique();        // DOC-00001
            $table->string('name');
            $table->string('specialization');             // Cardiology, General, etc
            $table->string('qualification');              // MBBS, MD, FCPS etc
            $table->string('phone');
            $table->string('email')->unique()->nullable();
            $table->string('cnic')->nullable()->unique();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('department');                 // Department name
            $table->decimal('consultation_fee', 10, 2)->default(0);
            $table->enum('availability', ['Available', 'On Leave', 'Off Duty'])->default('Available');
            $table->enum('shift', ['Morning', 'Evening', 'Night', 'Full Day'])->default('Morning');
            $table->time('shift_start')->nullable();
            $table->time('shift_end')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();          // photo path
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
