<?php

namespace App\Http\Livewire;

use App\Models\Channel;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Home extends Component
{
    public $search;

    public function render()
    {
        if (!($user = Auth::user())) {
            return redirect()->route('guest');
        }

        try {
            $channelId = Channel::whereExternalChannelId($user->external_id)->firstOrFail()->id;
        } catch (ModelNotFoundException $e) {
            \Auth::logout();
            return redirect()->route('guest')->withErrors(["This user does not have delete tracking set up."]);
        }
        $playlists = Playlist::whereChannelId($channelId)->where(
            'title',
            'like',
            sprintf('%%%s%%', addcslashes($this->search, '%_'))
        )->paginate();

        return view('livewire.home', compact('playlists'));
    }
}
