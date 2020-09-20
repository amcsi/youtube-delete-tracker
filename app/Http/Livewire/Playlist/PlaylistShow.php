<?php

namespace App\Http\Livewire\Playlist;

use App\Models\Playlist;
use Livewire\Component;

class PlaylistShow extends Component
{
    /**
     * @var Playlist|mixed
     */
    private $playlist;

    public function mount(Playlist $playlist): void
    {
        $this->playlist = $playlist;
    }

    public function render()
    {
        $videos = $this->playlist->videos()->paginate();

        return view('livewire.playlist.playlistShow', compact('videos'));
    }
}
