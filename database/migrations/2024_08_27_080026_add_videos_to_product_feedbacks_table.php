<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->string('videos')->nullable();
        });
    }

    /**
     * Откатите миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_feedbacks', function (Blueprint $table) {
            $table->dropColumn('videos');
        });
    }
};
