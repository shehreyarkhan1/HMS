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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // ── Who did it ──
            $table->unsignedBigInteger('user_id')->nullable();  // null = system/guest
            $table->string('user_name')->nullable();            // snapshot (user deleted hone pe bhi record rahe)
            $table->string('user_role')->nullable();            // snapshot of role at time of action

            // ── What they did ──
            $table->string('action');                           // e.g. 'created', 'updated', 'deleted', 'viewed', 'login', 'logout'
            $table->string('module');                           // e.g. 'Patient', 'Prescription', 'Billing'
            $table->string('description');                      // human-readable e.g. "Created patient John Doe"

            // ── On what ──
            $table->string('model_type')->nullable();           // e.g. 'App\Models\Patient'
            $table->unsignedBigInteger('model_id')->nullable(); // e.g. 45

            // ── Changes (for update actions) ──
            $table->json('old_values')->nullable();             // values before change
            $table->json('new_values')->nullable();             // values after change
            $table->json('extra')->nullable();                  // any extra context

            // ── Request info ──
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();           // GET, POST, PUT, DELETE

            // ── Severity ──
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('low');

            $table->timestamps();

            // ── Indexes for fast querying ──
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index(['model_type', 'model_id']);
            $table->index('severity');
            $table->index('created_at');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
