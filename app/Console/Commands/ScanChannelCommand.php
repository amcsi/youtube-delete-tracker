<?php

namespace App\Console\Commands;

use App\Channel\ChannelAdderAndScanner;
use App\Debug\Profiling;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ScanChannelCommand extends Command
{
    private const COMMAND = 'scan-channel';

    protected $signature = self::COMMAND . ' {channelId}';
    protected $description = 'Scans all playlists of a channel.';

    private $channelAdderAndScanner;

    public function __construct(ChannelAdderAndScanner $channelAdderAndScanner)
    {
        parent::__construct();
        $this->channelAdderAndScanner = $channelAdderAndScanner;
    }

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('scan-channel');
        $this->output->text('Started scanning channel.');

        $this->channelAdderAndScanner->addAndScan($this->argument('channelId'));

        $this->output->text('Done scanning channel. ' . Profiling::stopwatchToHuman($stopwatchEvent));
    }
}
