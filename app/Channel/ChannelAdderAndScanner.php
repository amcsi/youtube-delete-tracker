<?php

declare(strict_types=1);

namespace App\Models\Channel;

use Carbon\Carbon;

/**
 * Adds a channel (if it doesn't exist already) and scans it. Then marks that the channel was scanned.
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
        $channel = $this->channelAdder->addChannel($youtubeChannelId, true);
        $this->channelScanner->scan($youtubeChannelId);
        if ($channel) {
            $channel->last_scanned_at = Carbon::now();
            $channel->save();
        }
    }
}
