<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('want_advertising')->default(0)->after('role');
            $table->boolean('accept_policy')->default(0)->after('want_advertising');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('want_advertising');
            $table->dropColumn('accept_policy');
        });
    }
};
