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
        Schema::create('product_feedback_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feedback_id')->constrained('product_feedback')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('product_questions')->onDelete('cascade');
            $table->text('answer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_feedback_answers');
    }
};
