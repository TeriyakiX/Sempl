<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_popular')->default(false)->after('is_secret');
            $table->boolean('is_special')->default(false)->after('is_popular');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_popular');
            $table->dropColumn('is_special');
        });
    }
};
