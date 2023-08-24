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
        Schema::create('users', function (Blueprint $table) {
            $table->integerIncrements('userID')->unsigned(false);
            $table->string('userName', 255);
            $table->string('userPicture', 255);
            $table->string('userEmail', 255);
            $table->string('userPhoneNo',10)->unsigned(false);
            $table->string('userGender', 255);
            $table->string('userHobby', 255);
            $table->text("userMessage");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
