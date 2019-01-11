<?php
declare(strict_types=1);

namespace Tests\Unit\Channel;

use App\Channel\ChannelAdder;
use App\Channel\ChannelAdderAndScanner;
use App\Channel\ChannelScanner;
use Tests\TestCase;

class ChannelAdderAndScannerTest extends TestCase
{
    public function testAddAndScan()
    {
        $channelAdder = \Mockery::mock(ChannelAdder::class);
        $channelScanner = \Mockery::mock(ChannelScanner::class);
        $instance = new ChannelAdderAndScanner($channelAdder, $channelScanner);

        $channelId = 'channelId';

        $channelAdder->expects()->addChannel($channelId, true);
        $channelScanner->expects()->scan($channelId);

        $instance->addAndScan('channelId');
    }
}
