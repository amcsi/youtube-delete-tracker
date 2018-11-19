<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

class VideosByPlaylistLister
{
    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return \Google_Service_YouTube_PlaylistItemListResponse|\Google_Service_YouTube_PlaylistItem[]
     */
    public function list(string $playlistId): \Google_Service_YouTube_PlaylistItemListResponse
    {
        return $this->youtube->playlistItems->listPlaylistItems(
            'snippet',
            ['playlistId' => $playlistId, 'maxResults' => 50]
        );
    }
}
