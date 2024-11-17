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
        Schema::create('second_variant_combination', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('variant_combination_id');
            $table->foreignUuid('variant_value_id');
            $table->boolean('is_thumb');
            $table->decimal('price')->nullable();
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('second_variant_combination');
    }
};
