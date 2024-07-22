<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('feedback_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->timestamps();
        });

        Schema::create('feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('question_id');
            $table->string('answer');
            $table->timestamps();

            $table->foreign('question_id')->references('id')->on('feedback_questions')->onDelete('cascade');
        });

        Schema::table('product_feedback', function (Blueprint $table) {
            $table->unsignedBigInteger('fixed_question_1')->nullable();
            $table->unsignedBigInteger('fixed_question_2')->nullable();
            $table->unsignedBigInteger('fixed_question_3')->nullable();

            $table->foreign('fixed_question_1')->references('id')->on('feedback_answers')->onDelete('set null');
            $table->foreign('fixed_question_2')->references('id')->on('feedback_answers')->onDelete('set null');
            $table->foreign('fixed_question_3')->references('id')->on('feedback_answers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->dropForeign(['fixed_question_1']);
            $table->dropForeign(['fixed_question_2']);
            $table->dropForeign(['fixed_question_3']);
            $table->dropColumn(['fixed_question_1', 'fixed_question_2', 'fixed_question_3']);
        });

        Schema::dropIfExists('feedback_answers');
        Schema::dropIfExists('feedback_questions');
    }
};
