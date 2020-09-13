<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Removes the videos that had already been private or deleted in favorites.
 */
class RemoveDeletedVideoTitles extends Migration
{
    public function up()
    {
        \App\Models\Video::where('title', 'Private video')->orWhere('title', 'Deleted video')->delete();
    }
}
