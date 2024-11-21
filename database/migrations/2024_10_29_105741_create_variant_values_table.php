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
        Schema::create('variant_values', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('variant_id')->constrained();
            $table->boolean('is_thumb');
            $table->string('name');
            $table->decimal('price');
            $table->integer('available'); // number of items in inventory
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('variant_values');
    }
};
