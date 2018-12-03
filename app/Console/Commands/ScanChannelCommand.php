<?php

namespace App\Console\Commands;

use App\Debug\Profiling;
use App\ThirdParty\Youtube\Action\PlaylistsByChannelLister;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ScanChannelCommand extends Command
{
    private const COMMAND = 'scan-channel';

    protected $signature = self::COMMAND . ' {channelId}';
    protected $description = 'Scans all playlists of a channel.';

    private $playlistsByChannelLister;

    public function __construct(PlaylistsByChannelLister $playlistsByChannelLister)
    {
        parent::__construct();
        $this->playlistsByChannelLister = $playlistsByChannelLister;
    }

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('scan-channel');
        $this->output->text('Started scanning channel.');

        foreach ($this->playlistsByChannelLister->listAll($this->argument('channelId')) as $playlist) {
            $this->call(ScanPlaylistCommand::COMMAND, ['playlistId' => $playlist->id]);
        }

        $this->output->text('Done scanning channel. ' . Profiling::stopwatchToHuman($stopwatchEvent));
    }
}
