<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveVideosExternalChannelId extends Migration
{
    public function up()
    {
        Schema::table('videos',
            function (Blueprint $table) {
                $table->dropColumn('external_channel_id');
            });
    }

    public function down()
    {
        Schema::table('videos',
            function (Blueprint $table) {
                $table->string('external_channel_id')->default('')->after('external_video_id');
            });
    }
}
