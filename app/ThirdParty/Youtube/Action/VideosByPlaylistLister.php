<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

class VideosByPlaylistLister
{
    private const MAX_RESULTS = 50;

    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return \Google_Service_YouTube_PlaylistItem[]
     */
    public function listAll(string $playlistId): \Generator
    {
        $playlist = $this->list($playlistId);
        do {
            yield from $playlist;
        } while (($nextPageToken = $playlist->getNextPageToken()) && $playlist = $this->fetchNextPage(
            $playlistId,
            $nextPageToken
        ));
    }

    /**
     * Lists a page of playlist videos from the given playlist.
     *
     * @return \Google_Service_YouTube_PlaylistItemListResponse|\Google_Service_YouTube_PlaylistItem[]
     */
    public function list(string $playlistId, array $additionalParams = []): \Google_Service_YouTube_PlaylistItemListResponse
    {
        return $this->youtube->playlistItems->listPlaylistItems(
            'snippet',
            array_replace(['playlistId' => $playlistId, 'maxResults' => self::MAX_RESULTS], $additionalParams)
        );
    }

    /**
     * Lists the next page of playlist videos of a playlist response.
     *
     * @return \Google_Service_YouTube_PlaylistItemListResponse|\Google_Service_YouTube_PlaylistItem[]
     */
    public function fetchNextPage(string $playlistId, string $nextPageToken): \Google_Service_YouTube_PlaylistItemListResponse
    {
        return $this->list($playlistId, ['pageToken' => $nextPageToken]);
    }
}
