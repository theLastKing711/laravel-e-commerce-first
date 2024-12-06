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
            $table->ulid('id')->primary();
            $table->foreignUlid('variant_combination_id')
                ->constrained('variant_combination');
            $table->foreignUlid('variant_value_id')
                ->constrained('variant_values');
            $table->boolean('is_thumb');
            $table->decimal('price')->nullable();
            $table->integer('available');
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
