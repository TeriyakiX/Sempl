<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->boolean('liked_by_user')->default(false);
            $table->boolean('disliked_by_user')->default(false);
        });
    }

    public function down()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->dropColumn('liked_by_user');
            $table->dropColumn('disliked_by_user');
        });
    }

};
