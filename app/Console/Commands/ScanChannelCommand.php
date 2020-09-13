<?php

namespace App\Console\Commands;

use App\Channel;
use App\Channel\ChannelAdderAndScanner;
use App\Debug\Profiling;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ScanChannelCommand extends Command
{
    private const COMMAND = 'scan-channel';

    protected $signature = self::COMMAND.' {--all : Scan all tracked playlists} {channelId? : Scan the given youtube channel ID}';
    protected $description = 'Scans all playlists of a channel.';

    private $channelAdderAndScanner;

    public function __construct(ChannelAdderAndScanner $channelAdderAndScanner)
    {
        parent::__construct();
        $this->channelAdderAndScanner = $channelAdderAndScanner;
    }

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('scan-channels');
        $this->output->text('Started scanning channels.');

        if ($this->option('all')) {
            $youtubeChannelIds = Channel::whereTrack(true)->get(['external_channel_id'])->pluck('external_channel_id');
        } else {
            $youtubeChannelIds = [$this->argument('channelId')];
        }

        foreach ($youtubeChannelIds as $youtubeChannelId) {
            $channelEvent = (new Stopwatch())->start('scan-channel');
            $this->output->text("Started scanning channel $youtubeChannelId.");

            $this->channelAdderAndScanner->addAndScan($youtubeChannelId);

            $this->output->text('Done scanning channel. '.Profiling::stopwatchToHuman($channelEvent));
        }

        $this->output->text('Done scanning channels. '.Profiling::stopwatchToHuman($stopwatchEvent));
    }
}
