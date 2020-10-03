<?php

namespace App\Http\Livewire\Playlist;

use App\Database\DatabaseUtils;
use App\Models\Playlist;
use Livewire\Component;

class PlaylistShow extends Component
{
    public $search;

    protected $queryString = ['search'];

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
            DatabaseUtils::escapeLike($this->search)
        )->orderBy('known_deleted_at', 'desc')->paginate();

        return view('livewire.playlist.playlistShow', compact('videos'));
    }
}
