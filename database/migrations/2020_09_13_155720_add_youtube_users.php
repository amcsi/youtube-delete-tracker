<?php

use Database\Migrations\Tools\Tools;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYoutubeUsers extends Migration
{
    public function up()
    {
        Schema::create(
            'youtube_users',
            function (Blueprint $table) {
                $table->id();
                $table->string('external_id')->unique();
                $table->string('nickname');
                $table->rememberToken();
                Tools::timestamps($table);
            }
        );
    }

    public function down()
    {
        Schema::drop('youtube_users');
    }
}
