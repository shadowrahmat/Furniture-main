<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_furniture_requests', function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1)->after('height');
            $table->string('design_style')->nullable()->after('color_preference');
            $table->string('upholstery_fabric')->nullable()->after('design_style');
            $table->date('delivery_date')->nullable()->after('delivery_preference');
            $table->text('delivery_address')->nullable()->after('delivery_date');
            $table->text('customer_address')->nullable()->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('custom_furniture_requests', function (Blueprint $table) {
            $table->dropColumn([
                'quantity', 'design_style', 'upholstery_fabric',
                'delivery_date', 'delivery_address', 'customer_address',
            ]);
        });
    }
};
