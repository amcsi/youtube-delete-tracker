<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVideosTable extends Migration
{
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_video_id')->unique();
            $table->string('external_channel_id');
            $table->string('title');
            $table->timestamp('known_deleted_at')->nullable();
            $table->timestamp('updated_at')->useCurrent()->onUpdate('CURRENT_TIMESTAMP');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down()
    {
        Schema::dropIfExists('videos');
    }
}
