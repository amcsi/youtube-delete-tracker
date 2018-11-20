<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePlaylistVideoPivotTable extends Migration
{
    public function up()
    {
        Schema::create(
            'playlist_video',
            function (Blueprint $table) {
                $table->integer('playlist_id')->unsigned()->index();
                $table->foreign('playlist_id')->references('id')->on('playlists')->onDelete('cascade');
                $table->integer('video_id')->unsigned()->index();
                $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
                $table->primary(['playlist_id', 'video_id']);
            }
        );
    }

    public function down()
    {
        Schema::drop('playlist_video');
    }
}
