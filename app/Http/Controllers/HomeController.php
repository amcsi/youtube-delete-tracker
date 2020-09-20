<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Playlist;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
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
        $playlists = Playlist::whereChannelId($channelId)->get();

        return view('dashboard', compact('playlists'));
    }
}
