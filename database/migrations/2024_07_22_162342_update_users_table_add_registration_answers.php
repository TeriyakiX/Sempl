<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем старые поля
            $table->dropColumn([
                'people_living_with',
                'has_children',
                'pets',
                'average_monthly_income',
                'percentage_spent_on_cosmetics'
            ]);

            // Добавляем новые поля для хранения ID ответов
            $table->unsignedBigInteger('people_living_with_id')->nullable();
            $table->unsignedBigInteger('has_children_id')->nullable();
            $table->unsignedBigInteger('pets_id')->nullable();
            $table->unsignedBigInteger('average_monthly_income_id')->nullable();
            $table->unsignedBigInteger('percentage_spent_on_cosmetics_id')->nullable();

            // Добавляем внешние ключи
            $table->foreign('people_living_with_id')->references('id')->on('registration_answers')->onDelete('set null');
            $table->foreign('has_children_id')->references('id')->on('registration_answers')->onDelete('set null');
            $table->foreign('pets_id')->references('id')->on('registration_answers')->onDelete('set null');
            $table->foreign('average_monthly_income_id')->references('id')->on('registration_answers')->onDelete('set null');
            $table->foreign('percentage_spent_on_cosmetics_id')->references('id')->on('registration_answers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаляем новые поля
            $table->dropForeign(['people_living_with_id']);
            $table->dropForeign(['has_children_id']);
            $table->dropForeign(['pets_id']);
            $table->dropForeign(['average_monthly_income_id']);
            $table->dropForeign(['percentage_spent_on_cosmetics_id']);

            $table->dropColumn([
                'people_living_with_id',
                'has_children_id',
                'pets_id',
                'average_monthly_income_id',
                'percentage_spent_on_cosmetics_id'
            ]);

            // Добавляем старые поля обратно
            $table->string('people_living_with')->nullable();
            $table->string('has_children')->nullable();
            $table->string('pets')->nullable();
            $table->string('average_monthly_income')->nullable();
            $table->string('percentage_spent_on_cosmetics')->nullable();
        });
    }
};
