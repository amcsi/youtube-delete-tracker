<?php

declare(strict_types=1);

namespace App\Models\Video;

use App\Models\Channel\ChannelAdder;
use App\Models\Playlist;
use App\ThirdParty\Youtube\Action\PlaylistViewer;
use Psr\Log\LoggerInterface;

/**
 * Scans a playlist for videos.
 *
 * Adds new ones to the database.
 */
class PlaylistScanner
{
    private $playlistViewer;
    private $channelAdder;
    private $playlistVideosScanner;
    private $logger;

    public function __construct(
        PlaylistViewer $playlistViewer,
        ChannelAdder $channelAdder,
        PlaylistVideosScanner $playlistVideosScanner,
        LoggerInterface $logger
    ) {
        $this->playlistViewer = $playlistViewer;
        $this->channelAdder = $channelAdder;
        $this->logger = $logger;
        $this->playlistVideosScanner = $playlistVideosScanner;
    }

    public function scan(string $youtubePlaylistId): void
    {
        // First we'll need to make sure we have an up-to-date Eloquent model representation of the playlist.
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

        $this->playlistVideosScanner->scan($playlist);
    }
}
