<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChannelViewer
{
    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    public function view(string $channelId): \Google_Service_YouTube_Channel
    {
        $channel = $this->youtube->channels->listChannels(
            'snippet',
            ['id' => $channelId, 'maxResults' => 1]
        )->current();
        if (!$channel) {
            throw new NotFoundHttpException("Could not find channel with an ID of $channelId");
        }
        return $channel;
    }
}
