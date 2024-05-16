<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('delivery_address');
            $table->string('city');
            $table->string('street');
            $table->string('house_number');
            $table->string('apartment_number')->nullable();
            $table->string('entrance')->nullable();
            $table->string('postal_code');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_address',
                'city',
                'street',
                'house_number',
                'apartment_number',
                'entrance',
                'postal_code'
            ]);
        });
    }
};
