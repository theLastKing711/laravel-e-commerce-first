<?php

use App\Enum\Unit;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('name');
            $table->decimal('price');
            $table->string('hash')->nullable();
            $table->string('description')->nullable();
            $table->decimal('price_offer')->nullable();
            $table->boolean('is_most_buy');
            $table->boolean('is_active');
            $table->enum('unit', Unit::asValuesArray())->nullable();
            $table->integer('unit_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
