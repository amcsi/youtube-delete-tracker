<?php
declare(strict_types=1);

namespace App\Video;

use App\Playlist;
use App\ThirdParty\Youtube\Action\VideosByPlaylistLister;
use App\Video;

class PlaylistVideosScanner
{
    private $videosByPlaylistLister;
    private $missingChecker;

    public function __construct(
        VideosByPlaylistLister $videosByPlaylistLister,
        PlaylistMissingVideoChecker $missingChecker
    ) {
        $this->videosByPlaylistLister = $videosByPlaylistLister;
        $this->missingChecker = $missingChecker;
    }

    public function scan(Playlist $playlist): void
    {
        $videoIds = Video::unguarded(
            function () use ($playlist) {
                $existingVideosKeyByExternalId = $playlist->videosNotRemotelyDeleted->keyBy('external_video_id');

                $results = $this->videosByPlaylistLister->listAll($playlist->external_playlist_id);

                $videoIds = [];
                foreach ($results as $result) {
                    $videoId = VideoUpserter::upsertFromPlaylistItem($result);
                    if ($videoId === null) {
                        continue;
                    }
                    $videoIds[] = $videoId;
                    unset($existingVideosKeyByExternalId[$result->getSnippet()->getResourceId()->videoId]);
                }

                $this->missingChecker->investigateMissing($playlist, $existingVideosKeyByExternalId);

                return $videoIds;
            });

        // Many-to-many relationship.
        $playlist->videos()->sync($videoIds, false);
    }
}
