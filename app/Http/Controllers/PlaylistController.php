<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Playlist;

class PlaylistController
{
    public function show(Playlist $playlist)
    {
        $videos = $playlist->videos()->paginate();

        return view('playlist/playlistShow', compact('videos'));
    }
}
