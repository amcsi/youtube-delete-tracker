<?php

use database\migrations\tools\Tools;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_video_id')->unique();
            $table->string('external_channel_id')->default('');
            $table->string('title');
            $table->timestamp('known_deleted_at')->nullable();
            Tools::timestamps($table);
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
