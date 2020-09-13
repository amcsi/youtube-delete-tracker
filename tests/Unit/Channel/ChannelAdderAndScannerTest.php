<?php
declare(strict_types=1);

namespace Tests\Unit\Channel;

use App\Channel\ChannelAdder;
use App\Channel\ChannelAdderAndScanner;
use App\Channel\ChannelScanner;
use App\Models\Channel;
use Carbon\Carbon;
use Mockery\Matcher\Type;
use Tests\TestCase;

class ChannelAdderAndScannerTest extends TestCase
{
    public function testAddAndScan()
    {
        $channelAdder = \Mockery::mock(ChannelAdder::class);
        $channelScanner = \Mockery::mock(ChannelScanner::class);
        $instance = new ChannelAdderAndScanner($channelAdder, $channelScanner);

        $channelId = 'channelId';

        $channel = \Mockery::mock(Channel::class);
        $channelExpects = $channel->expects();
        $channelExpects->setAttribute('last_scanned_at', new Type(Carbon::class));
        $channelExpects->save();

        $channelAdder->expects()->addChannel($channelId, true)->andReturn($channel);
        $channelScanner->expects()->scan($channelId);

        $instance->addAndScan('channelId');
    }
}
