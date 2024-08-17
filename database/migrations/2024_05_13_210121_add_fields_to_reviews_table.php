<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('pros')->nullable();
            $table->text('cons')->nullable();
            $table->json('media')->nullable();
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'pros')) {
                $table->dropColumn('pros');
            }

            if (Schema::hasColumn('reviews', 'cons')) {
                $table->dropColumn('cons');
            }

            if (Schema::hasColumn('reviews', 'media')) {
                $table->dropColumn('media');
            }
        });
    }
};
