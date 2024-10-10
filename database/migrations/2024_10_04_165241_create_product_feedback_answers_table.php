<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('product_feedback')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('product_questions')->onDelete('cascade');
            $table->unsignedBigInteger('answer_id')->nullable(); // Поле для связи с ответом
            $table->timestamps();

            // Добавление внешнего ключа для поля answer_id, ссылаясь на product_question_answers
            $table->foreign('answer_id')->references('id')->on('product_question_answers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_feedback_answers', function (Blueprint $table) {
            $table->dropForeign(['answer_id']); // Удаляем внешний ключ
        });

        Schema::dropIfExists('product_feedback_answers');
    }
};
