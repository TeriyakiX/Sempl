<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->foreignId('delivery_status_id')->default(1)->constrained('delivery_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sample_requests', function (Blueprint $table) {
            $table->dropForeign(['delivery_status_id']);
            $table->dropColumn('delivery_status_id');
        });
    }
};
