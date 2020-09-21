<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

class VideoLister
{
    private const MAX_RESULTS = 50;

    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    /**
     * @return \Google_Service_YouTube_Video[]
     */
    public function listAll(array $params): \Generator
    {
        $playlist = $this->list($params);
        do {
            yield from $playlist;
        } while (($nextPageToken = $playlist->getNextPageToken()) && $playlist = $this->list(
            array_replace($params, ['pageToken' => $nextPageToken]),
        ));
    }

    /**
     * Lists a page of videos.
     *
     * @return \Google_Service_YouTube_VideoListResponse|\Google_Service_YouTube_Video[]
     */
    public function list(array $params = []): \Google_Service_YouTube_VideoListResponse
    {
        return $this->youtube->videos->listVideos(
            'id',
            array_replace(['maxResults' => self::MAX_RESULTS], $params)
        );
    }
}
