<?php

namespace App\Http\Livewire\Playlist;

use App\Models\Playlist;
use Livewire\Component;

class PlaylistShow extends Component
{
    public $search;

    /**
     * @var Playlist|mixed
     */
    public $playlist;

    public function mount(Playlist $playlist): void
    {
        $this->playlist = $playlist;
    }

    public function render()
    {
        $videos = $this->playlist->videos()->where(
            'title',
            'like',
            sprintf('%%%s%%', addcslashes($this->search, '%_'))
        )->orderBy('known_deleted_at', 'desc')->paginate();

        return view('livewire.playlist.playlistShow', compact('videos'));
    }
}
