<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_furniture_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('furniture_type_id')->nullable()->constrained('furniture_types')->nullOnDelete();
            $table->foreignId('wood_type_id')->nullable()->constrained('wood_types')->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('dimension_unit')->default('cm');
            $table->string('finish')->nullable();
            $table->string('color_preference')->nullable();
            $table->decimal('budget_min', 10, 2)->nullable();
            $table->decimal('budget_max', 10, 2)->nullable();
            $table->string('delivery_preference')->nullable();
            $table->text('additional_notes')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_furniture_requests');
    }
};
