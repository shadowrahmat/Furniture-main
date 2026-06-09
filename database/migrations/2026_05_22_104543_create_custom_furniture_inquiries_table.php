<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_furniture_inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('furniture_type');
            $table->string('space_type')->nullable();
            $table->string('dimensions')->nullable();
            $table->string('material_preference')->nullable();
            $table->string('color_preference')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->text('description');
            $table->string('reference_image')->nullable();
            $table->string('status')->default('new');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_furniture_inquiries');
    }
};
