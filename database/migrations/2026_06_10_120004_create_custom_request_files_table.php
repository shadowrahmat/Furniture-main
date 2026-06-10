<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_request_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_furniture_request_id')->constrained('custom_furniture_requests')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->string('uploaded_by')->default('customer');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_request_files');
    }
};
