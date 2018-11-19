<?php
declare(strict_types=1);

namespace App\Video;

use App\ThirdParty\Youtube\Action\VideosByPlaylistLister;
use App\Video;

/**
 * Scans a playlist for videos.
 *
 * Adds new ones to the database.
 */
class PlaylistScanner
{
    private $videosByPlaylistLister;

    public function __construct(VideosByPlaylistLister $videosByPlaylistLister)
    {
        $this->videosByPlaylistLister = $videosByPlaylistLister;
    }

    public function scan(string $playlistId): void
    {
        Video::unguarded(function () use ($playlistId) {
            $results = $this->videosByPlaylistLister->listAll($playlistId);

            foreach ($results as $result) {
                $snippet = $result->getSnippet();
                Video::updateOrCreate([
                    'external_video_id' => $snippet->getResourceId()->videoId,
                    'title' => $snippet->title,
                ]);
            }
        });
    }
}
