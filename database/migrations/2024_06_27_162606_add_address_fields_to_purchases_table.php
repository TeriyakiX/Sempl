<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('delivery_address')->nullable();
            $table->string('city')->nullable();
            $table->string('street')->nullable();
            $table->string('house_number')->nullable();
            $table->string('apartment_number')->nullable();
            $table->string('entrance')->nullable();
            $table->string('postal_code')->nullable();
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_address',
                'city',
                'street',
                'house_number',
                'apartment_number',
                'entrance',
                'postal_code',
            ]);
        });
    }
};
