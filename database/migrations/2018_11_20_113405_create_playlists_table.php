<?php

use database\migrations\tools\Tools;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlaylistsTable extends Migration
{
    public function up()
    {
        Schema::create(
            'playlists',
            function (Blueprint $table) {
                $table->increments('id');
                $table->string('external_playlist_id')->unique();
                $table->string('external_channel_id')->default('');
                $table->string('title');
                Tools::timestamps($table);
            }
        );
    }

    public function down()
    {
        Schema::dropIfExists('playlists');
    }
}
