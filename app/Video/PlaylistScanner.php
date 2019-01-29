<?php
declare(strict_types=1);

namespace App\Video;

use App\Channel\ChannelAdder;
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
    private $channelAdder;
    private $logger;

    public function __construct(
        VideosByPlaylistLister $videosByPlaylistLister,
        PlaylistViewer $playlistViewer,
        ChannelAdder $channelAdder,
        LoggerInterface $logger
    ) {
        $this->videosByPlaylistLister = $videosByPlaylistLister;
        $this->playlistViewer = $playlistViewer;
        $this->channelAdder = $channelAdder;
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

                $channel = $this->channelAdder->addChannel($playlistSnippet->channelId, false);

                // Create the playlist.
                return Playlist::updateOrCreate(
                    [
                        'external_playlist_id' => $youtubePlaylistId,
                    ],
                    [
                        'channel_id' => $channel->id,
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
                    $videoIds[] = VideoUpserter::upsertFromPlaylistItem($result);
                }
                return $videoIds;
            });

        // Many-to-many relationship.
        $playlist->videos()->sync($videoIds, false);
    }
}
