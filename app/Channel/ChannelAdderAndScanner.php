<?php
declare(strict_types=1);

namespace App\Channel;

/**
 * Adds a channel and scans it.
 */
class ChannelAdderAndScanner
{
    private $channelAdder;
    private $channelScanner;

    public function __construct(ChannelAdder $channelAdder, ChannelScanner $channelScanner)
    {
        $this->channelAdder = $channelAdder;
        $this->channelScanner = $channelScanner;
    }

    public function addAndScan(string $youtubeChannelId): void
    {
        $this->channelAdder->addChannel($youtubeChannelId, true);
        $this->channelScanner->scan($youtubeChannelId);
    }
}
