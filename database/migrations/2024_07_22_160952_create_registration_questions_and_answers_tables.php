<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Создание таблицы вопросов
        Schema::create('registration_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->timestamps();
        });

        // Создание таблицы ответов
        Schema::create('registration_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->string('answer');
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('registration_questions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('registration_answers');
        Schema::dropIfExists('registration_questions');
    }
};
