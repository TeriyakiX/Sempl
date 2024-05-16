<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('people_living_with')->nullable();
            $table->boolean('has_children')->default(false);
            $table->string('pets')->nullable();
            $table->decimal('average_monthly_income', 10, 2)->nullable();
            $table->decimal('percentage_spent_on_cosmetics', 5, 2)->nullable();
            $table->string('vk_profile')->nullable();
            $table->string('telegram_profile')->nullable();
            $table->string('profile_photo')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'people_living_with',
                'has_children',
                'pets',
                'average_monthly_income',
                'percentage_spent_on_cosmetics',
                'vk_profile',
                'telegram_profile',
                'profile_photo',
            ]);
        });
    }
};
