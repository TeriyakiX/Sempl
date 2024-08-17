<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if column exists before dropping it
        if (Schema::hasColumn('reviews', 'likes_count')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('likes_count');
            });
        }
    }

    public function down()
    {
        // If the column was dropped, you can add it back in the down method
        if (!Schema::hasColumn('reviews', 'likes_count')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->integer('likes_count')->default(0);
            });
        }
    }
};
