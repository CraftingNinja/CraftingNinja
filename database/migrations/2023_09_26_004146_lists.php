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
        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id');
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('item_list', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id');
            // Not using foreignId; item and recipe are on a separate database.
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('recipe_id')->nullable();
            $table->unsignedSmallInteger('quantity')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lists');
        Schema::dropIfExists('item_list');
    }
};
