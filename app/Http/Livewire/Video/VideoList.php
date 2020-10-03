<?php
declare(strict_types=1);

namespace App\Http\Livewire\Video;

use App\Models\Video;
use App\Models\YoutubeUser;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class VideoList extends Component
{
    /**
     * @var Video[]
     */
    private $videos;

    public function mount(): void
    {
        /** @var YoutubeUser $youtubeUser */
        $youtubeUser = \Auth::authenticate();
        $playlists = $youtubeUser->channel->playlists;
        $this->videos = Video::whereHas(
            'playlists',
            function (Builder $builder) use ($playlists) {
                $builder->whereIn('id', $playlists->pluck('id'));
            }
        )->whereNotNull('known_deleted_at')->paginate();
    }


    public function render()
    {
        $videos = $this->videos;

        return view('livewire.video.video-list', compact('videos'));
    }
}
