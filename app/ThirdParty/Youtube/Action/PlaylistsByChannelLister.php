<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

/**
 * Lists playlists by a user.
 */
class PlaylistsByUserLister
{
    private const MAX_RESULTS = 50;

    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    public function listAll(
        string $channelId,
        array $additionalParams = []
    ): \Google_Service_YouTube_PlaylistItemListResponse {
        return $this->youtube->playlistItems->listPlaylistItems(
            'snippet',
            array_replace(['channelId' => $channelId, 'maxResults' => self::MAX_RESULTS], $additionalParams)
        );
    }
}
