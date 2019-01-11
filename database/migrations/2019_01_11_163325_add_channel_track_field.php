<?php

use App\Channel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Adds a field to mark that a channel is to be fully tracked.
 */
class AddChannelTrackField extends Migration
{
    public function up()
    {
        Schema::table('channels',
            function (Blueprint $table) {
                $table->boolean('track')->default(false)->after('name');
            });

        // Mark all the current ones as ones to be tracked.
        Channel::query()->update(['track' => true]);
    }

    public function down()
    {
        Schema::table('channels',
            function (Blueprint $table) {
                $table->dropColumn('track');
            });
    }
}
