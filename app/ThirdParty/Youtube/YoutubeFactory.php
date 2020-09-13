<?php

declare(strict_types=1);

namespace App\ThirdParty\Youtube;

/**
 * Creates an instance of a Youtube Client.
 */
class YoutubeFactory
{
    private $client;

    public function __construct(\Google_Client $client)
    {
        $this->client = $client;
    }

    public function __invoke(): \Google_Service_YouTube
    {
        $client = new \Google_Client();
        $client->setDeveloperKey(getenv('YOUTUBE_API_KEY'));

        return new \Google_Service_YouTube($client);
    }
}
