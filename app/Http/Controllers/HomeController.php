<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Playlist;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (!($user = Auth::user())) {
            return redirect()->route('guest');
        }

        $channelId = Channel::whereExternalChannelId($user->external_id)->firstOrFail()->id;
        $playlists = Playlist::whereChannelId($channelId)->get();

        return view('dashboard', compact('playlists'));
    }
}
