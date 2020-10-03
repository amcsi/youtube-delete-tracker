<?php
declare(strict_types=1);

namespace App\Http\Livewire\Video;

use App\Database\DatabaseUtils;
use App\Models\Video;
use App\Models\YoutubeUser;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class VideoList extends Component
{
    public $search;

    public function render()
    {
        /** @var YoutubeUser $youtubeUser */
        $youtubeUser = \Auth::authenticate();
        $playlists = $youtubeUser->channel->playlists;
        $videos = Video::whereHas(
            'playlists',
            function (Builder $builder) use ($playlists) {
                $builder->whereIn('id', $playlists->pluck('id'));
            }
        )->whereNotNull('known_deleted_at')->where(
            'title',
            'like',
            DatabaseUtils::escapeLike($this->search)
        )->paginate();

        return view('livewire.video.video-list', compact('videos'));
    }
}
