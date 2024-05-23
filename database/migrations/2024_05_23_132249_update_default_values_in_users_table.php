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
        Schema::table('users', function (Blueprint $table) {
            $table->string('login')->nullable()->default('')->change();
            $table->string('password')->nullable()->default('')->change();
            $table->string('first_name')->nullable()->default('')->change();
            $table->string('last_name')->nullable()->default('')->change();
            $table->string('gender')->nullable()->default('')->change();
            $table->date('birthdate')->nullable()->change();
            $table->string('app_name')->nullable()->default('')->change();
            $table->string('email')->nullable()->default('')->change();
            $table->string('address')->nullable()->default('')->change();
            $table->integer('role')->default(0)->change();
            $table->integer('people_living_with')->nullable()->change();
            $table->boolean('has_children')->default(0)->change();
            $table->text('pets')->nullable()->change();
            $table->decimal('average_monthly_income')->nullable()->change();
            $table->decimal('percentage_spent_on_cosmetics')->nullable()->change();
            $table->text('vk_profile')->nullable()->change();
            $table->string('telegram_profile')->nullable()->change();
            $table->string('profile_photo')->nullable()->change();
            $table->string('delivery_address')->nullable()->default('')->change();
            $table->string('city')->nullable()->default('')->change();
            $table->string('street')->nullable()->default('')->change();
            $table->string('house_number')->nullable()->default('')->change();
            $table->string('apartment_number')->nullable()->change();
            $table->string('entrance')->nullable()->change();
            $table->string('postal_code')->nullable()->default('')->change();
            $table->string('phone')->nullable()->default('')->change();
            $table->integer('verification_code')->nullable()->default(0)->change();
            $table->dateTime('code_sent_at')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
