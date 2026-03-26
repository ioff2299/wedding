<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('user_token')->unique();
            $table->string('name');
            $table->boolean('attending');
            $table->string('food_preference')->nullable();
            $table->json('alcohol_preferences')->nullable();
            $table->string('food_allergy')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guests');
    }
};
