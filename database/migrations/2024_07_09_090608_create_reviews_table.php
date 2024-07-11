<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('question_1')->nullable();
            $table->unsignedTinyInteger('question_2')->nullable();
            $table->unsignedTinyInteger('question_3')->nullable();
            $table->text('description')->nullable();
            $table->boolean('pro_1')->nullable();
            $table->boolean('pro_2')->nullable();
            $table->boolean('con_1')->nullable();
            $table->boolean('con_2')->nullable();
            $table->text('photos')->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
