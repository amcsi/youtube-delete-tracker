<?php

use App\Channel;
use App\Playlist;
use database\migrations\tools\Tools;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    public function up()
    {
        try {
            DB::beginTransaction();

            Schema::create('channels',
                function (Blueprint $table) {
                    $table->increments('id');
                    $table->string('external_channel_id')->unique();
                    $table->string('name');
                    Tools::timestamps($table);
                });

            Schema::table('playlists', function (Blueprint $table) {
                $table->unsignedInteger('channel_id')->after('external_channel_id');
                Schema::disableForeignKeyConstraints();
                $table->foreign('channel_id')->references('id')->on('channels')->after('external_channel_id');
            });

            // Convert the old external channel ids into internal channel ids.
            $externalChannelIds = Playlist::distinct()->get(['external_channel_id'])->pluck('external_channel_id');
            foreach ($externalChannelIds as $externalChannelId) {
                $channelId = Channel::create([
                    'external_channel_id' => $externalChannelId,
                    'name' => '',
                ])->id;

                Playlist::whereExternalChannelId($externalChannelId)->update(['channel_id' => $channelId]);
            }

            Schema::table('playlists', function (Blueprint $table) {
                Schema::enableForeignKeyConstraints();
                $table->dropColumn('external_channel_id');
            });

            DB::commit();
        } catch (PDOException $exception) {
            DB::rollBack();
            Schema::dropIfExists('channels');
            throw $exception;
        }
    }

    public function down()
    {
        DB::transaction(function () {
            Schema::table('playlists', function (Blueprint $table) {
                $table->string('external_channel_id')->after('channel_id');
            });

            $channels = Playlist::distinct()->get(['channel_id'])->load('channel');

            // Convert the old external channel ids into internal channel ids.
            foreach ($channels as $externalChannelId) {
                Playlist::whereExternalChannelId($externalChannelId)
                    ->update(['external_channel_id' => $externalChannelId]);
            }

            Schema::table('playlists', function (Blueprint $table) {
                $table->dropForeign('playlists_channel_id_foreign');
                $table->dropColumn('channel_id');
            });

            Schema::dropIfExists('channels');
        });
    }
}
