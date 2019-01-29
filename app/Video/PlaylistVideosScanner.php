<?php
declare(strict_types=1);

namespace App\Video;

use App\Playlist;
use App\ThirdParty\Youtube\Action\VideosByPlaylistLister;
use App\Video;

class PlaylistVideosScanner
{
    private $videosByPlaylistLister;

    public function __construct(VideosByPlaylistLister $videosByPlaylistLister)
    {
        $this->videosByPlaylistLister = $videosByPlaylistLister;
    }

    public function scan(Playlist $playlist): void
    {
        $videoIds = Video::unguarded(
            function () use ($playlist) {
                $results = $this->videosByPlaylistLister->listAll($playlist->external_playlist_id);

                $videoIds = [];
                foreach ($results as $result) {
                    $videoIds[] = VideoUpserter::upsertFromPlaylistItem($result);
                }
                return $videoIds;
            });

        // Many-to-many relationship.
        $playlist->videos()->sync($videoIds, false);
    }
}
