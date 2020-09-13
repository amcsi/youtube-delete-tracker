<?php

declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

/**
 * Lists playlists by a user.
 */
class PlaylistsByChannelLister
{
    private const MAX_RESULTS = 50;

    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return \Google_Service_YouTube_Playlist[]|\Generator
     */
    public function listAll(
        string $channelId
    ): \Generator {
        $channel = $this->list($channelId);
        do {
            yield from $channel;
        } while (($nextPageToken = $channel->getNextPageToken()) && $channel = $this->list(
            $channelId,
            ['pageToken' => $nextPageToken]
        ));
    }

    /**
     * Lists a page of playlists from the given channel.
     *
     * @return \Google_Service_YouTube_Playlist[]|\Google_Service_YouTube_PlaylistListResponse
     */
    public function list(string $channelId, array $additionalParams = []): \Google_Service_YouTube_PlaylistListResponse
    {
        return $this->youtube->playlists->listPlaylists(
            'snippet',
            array_replace(['channelId' => $channelId, 'maxResults' => self::MAX_RESULTS], $additionalParams)
        );
    }
}
