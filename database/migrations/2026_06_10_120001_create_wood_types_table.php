<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wood_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('durability')->nullable();
            $table->string('recommended_use')->nullable();
            $table->string('color_texture')->nullable();
            $table->string('price_adjustment_type')->default('fixed');
            $table->decimal('price_adjustment_value', 10, 2)->default(0);
            $table->string('availability')->default('in_stock');
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wood_types');
    }
};
