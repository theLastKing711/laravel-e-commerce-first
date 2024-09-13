<?php

use App\Enum\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->decimal('total');
            $table->integer('status');
            $table->string('rejection_reason')->nullable();
            $table->dateTime('required_time');
            $table->string('notice')->nullable();
            $table->double('lat');
            $table->double('lon');
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('on_the_way_at')->nullable();
            $table->dateTime('rejected_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->decimal('delivery_price');

            $table->foreignId('user_id')->constrained();
            $table->foreignId('coupon_id')->nullable()->constrained();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
