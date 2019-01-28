<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChannelLastScannedAtField extends Migration
{
    public function up()
    {
        Schema::table('channels',
            function (Blueprint $table) {
                $table->timestamp('last_scanned_at')->nullable();
            });
    }

    public function down()
    {
        Schema::table('channels',
            function (Blueprint $table) {
                $table->dropColumn('last_scanned_at');
            });
    }
}
