<?php

declare(strict_types=1);

namespace App\Channel;

use App\Channel;
use App\ThirdParty\Youtube\Action\ChannelViewer;

class ChannelAdder
{
    private $channelViewer;

    public function __construct(ChannelViewer $channelViewer)
    {
        $this->channelViewer = $channelViewer;
    }

    /**
     * Ensures the given Youtube channel is in the database, and returns its model.
     */
    public function addChannel(string $youtubeChannelId, bool $track): Channel
    {
        $youtubeChannel = $this->channelViewer->view($youtubeChannelId);
        $values = [
            'name' => $youtubeChannel->getSnippet()->title,
        ];
        if ($track) {
            $values['track'] = true;
        }

        return Channel::updateOrCreate(['external_channel_id' => $youtubeChannel->id], $values);
    }
}
