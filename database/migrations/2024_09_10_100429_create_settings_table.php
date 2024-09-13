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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->float('km_price');
            $table->float('open_km_price');
            $table->float('order_delivery_min_distance');
            $table->float('order_delivery_min_item_per_order');
            $table->float('min_order_item_quantity_for_free_delivery');
            $table->double('store_lat');
            $table->double('store_lon');
            $table->string('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
