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
        Schema::create('variant_combination', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('first_variant_value_id');
            $table->foreignUuid('second_variant_value_id');
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
        Schema::dropIfExists('variant_combination');
    }
};
