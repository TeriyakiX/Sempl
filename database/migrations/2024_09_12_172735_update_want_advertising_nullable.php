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
            // Установите значение по умолчанию false и не допускайте NULL
            $table->boolean('want_advertising')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Верните поле в состояние, допускающее NULL, если нужно
            $table->boolean('want_advertising')->nullable()->default(false)->change();
        });
    }
};
