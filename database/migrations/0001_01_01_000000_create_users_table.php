<?php

use App\Enum\AccountRegistrationStep;
use App\Enum\Gender;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();

            //User columns
            $table->integer('gender')->nullable(); //Gender enum
            $table->string('number')->nullable();
            $table->string('dial_code')->nullable();
            $table->enum('account_registration_step', AccountRegistrationStep::asValuesArray())
                ->nullable();
            $table->string('code')->nullable();
            $table->string('temp_number')->nullable();
            $table->string('temp_dial_code')->nullable();
            $table->string('temp_code')->nullable();

            //Driver columns
            $table->string('username')->nullable();
            $table->double('lat')->nullable();
            $table->double('lon')->nullable();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
