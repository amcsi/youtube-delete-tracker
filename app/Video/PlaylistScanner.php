<?php
declare(strict_types=1);

namespace App\Video;

use App\Playlist;
use App\ThirdParty\Youtube\Action\PlaylistViewer;
use App\ThirdParty\Youtube\Action\VideosByPlaylistLister;
use App\Video;
use Psr\Log\LoggerInterface;

/**
 * Scans a playlist for videos.
 *
 * Adds new ones to the database.
 */
class PlaylistScanner
{
    private $videosByPlaylistLister;
    private $playlistViewer;
    private $logger;

    public function __construct(
        VideosByPlaylistLister $videosByPlaylistLister,
        PlaylistViewer $playlistViewer,
        LoggerInterface $logger
    )
    {
        $this->videosByPlaylistLister = $videosByPlaylistLister;
        $this->playlistViewer = $playlistViewer;
        $this->logger = $logger;
    }

    public function scan(string $youtubePlaylistId): void
    {
        $youtubePlaylist = $this->playlistViewer->view($youtubePlaylistId);
        $title = $youtubePlaylist->snippet->title;
        $this->logger->info("Scanning playlist $youtubePlaylistId ($title)");
        /** @var Playlist $playlist */
        $playlist = Playlist::unguarded(
            function () use ($youtubePlaylistId, $youtubePlaylist) {
                $playlistSnippet = $youtubePlaylist->getSnippet();
                // Create the playlist.
                return Playlist::updateOrCreate(
                    [
                        'external_playlist_id' => $youtubePlaylistId,
                        'external_channel_id' => $playlistSnippet->channelId,
                        'title' => $playlistSnippet->title,
                    ]
                );
            }
        );

        $videoIds = Video::unguarded(
            function () use ($youtubePlaylistId) {
                $results = $this->videosByPlaylistLister->listAll($youtubePlaylistId);

                $videoIds = [];
                foreach ($results as $result) {
                    $snippet = $result->getSnippet();
                    $video = Video::updateOrCreate(
                        [
                            'external_video_id' => $snippet->getResourceId()->videoId,
                            'title' => $snippet->title,
                        ]);
                    $videoIds[] = $video->id;
                }
                return $videoIds;
            });

        // Many-to-many relationship.
        $playlist->videos()->sync($videoIds, false);
    }
}
