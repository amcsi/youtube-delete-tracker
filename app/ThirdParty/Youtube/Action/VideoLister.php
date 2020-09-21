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
    public function listAll(array $videoIds): \Generator
    {
        // https://stackoverflow.com/a/36371390/1381550
        $chunks = array_chunk($videoIds, config('custom.youtube.idListChunkSize'));

        foreach ($chunks as $chunk) {
            yield from $this->list(['id' => implode(',', $chunk)]);
        }
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
