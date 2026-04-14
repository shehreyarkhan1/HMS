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
        Schema::create('radiology_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('radiology_order_item_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('file_path');                    // Storage path
            $table->string('file_name');                    // Original filename
            $table->string('file_type')->default('image');  // image, dicom, pdf
            $table->string('mime_type')->nullable();
            $table->bigInteger('file_size')->nullable();    // bytes

            // ── DICOM metadata (if applicable) ──
            $table->string('dicom_series_uid')->nullable();
            $table->string('dicom_instance_uid')->nullable();
            $table->string('view_position')->nullable();    // PA, AP, Lateral, Oblique
            $table->text('dicom_metadata')->nullable();     // JSON blob

            $table->boolean('is_primary')->default(false);  // Main image for report
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('radiology_order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('radiology_images');
    }
};
