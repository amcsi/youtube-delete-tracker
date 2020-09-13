<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\YoutubeUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class YoutubeLoginController extends Controller
{
    public function login()
    {
        return Socialite::driver('youtube')->redirect();
    }

    public function callback()
    {
        $externalYoutubeUser = Socialite::driver('youtube')->user();

        if (!($youtubeUser = YoutubeUser::whereExternalId($externalId = $externalYoutubeUser->getId())->first())) {
            $youtubeUser = new YoutubeUser();
            $youtubeUser->external_id = $externalId;
            $youtubeUser->nickname = $externalYoutubeUser->getNickname();
            $youtubeUser->save();
        }

        Auth::login($youtubeUser, true);

        return redirect()->intended();
    }
}
