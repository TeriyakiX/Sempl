<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_feedback_answers', function (Blueprint $table) {
            if (!Schema::hasColumn('product_feedback_answers', 'question_id')) {
                $table->unsignedBigInteger('question_id');
            }

            if (!Schema::hasColumn('product_feedback_answers', 'answer_id')) {
                $table->unsignedBigInteger('answer_id');
            }

            $table->foreign('question_id')
                ->references('id')
                ->on('feedback_questions')
                ->onDelete('cascade');

            $table->foreign('answer_id')
                ->references('id')
                ->on('feedback_answers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_feedback_answers', function (Blueprint $table) {
            $table->dropForeign(['question_id']);
            $table->dropForeign(['answer_id']);

            $table->dropColumn(['question_id', 'answer_id']);
        });
    }
};
