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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('order_id')->constrained();
            $table->foreignUlid('product_id')->constrained();
            $table->foreignUlid('variant_value_id')
                ->nullable()
                ->constrained('variant_values');
            $table->foreignUlid('variant_combination_id')
                ->nullable()
                ->constrained('variant_combination');
            $table->foreignUlid('second_variant_combination_id')
                ->nullable()
                ->constrained('second_variant_combination');
            $table->decimal('unit_price')->nullable();
            $table->decimal('unit_price_offer')->nullable();
            $table->integer('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
