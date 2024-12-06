<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->text('media')->nullable();
        });

        // Объединяем данные из старых полей
        $feedbacks = DB::table('product_feedback')->get();

        foreach ($feedbacks as $feedback) {
            $photos = $feedback->photos ? json_decode($feedback->photos, true) : [];
            $videos = $feedback->videos ? json_decode($feedback->videos, true) : [];

            $media = [];

            foreach ($photos as $photo) {
                $media[] = ['type' => 'photo', 'path' => $photo];
            }

            foreach ($videos as $video) {
                $media[] = ['type' => 'video', 'path' => $video];
            }

            DB::table('product_feedback')
                ->where('id', $feedback->id)
                ->update(['media' => json_encode($media)]);
        }

        // Удаляем старые поля
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->dropColumn(['photos', 'videos']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('product_feedback', function (Blueprint $table) {
            $table->string('photos', 255)->nullable();
            $table->string('videos', 255)->nullable();
            $table->dropColumn('media');
        });
    }
};
