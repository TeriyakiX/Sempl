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
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->date('delivery_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->dropColumn('delivery_date');
        });
    }
};
