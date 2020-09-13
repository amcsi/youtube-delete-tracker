<?php

namespace App\Console\Commands;

use App\Debug\Profiling;
use App\Models\Video\PlaylistScanner;
use Illuminate\Console\Command;
use Symfony\Component\Stopwatch\Stopwatch;

class ScanPlaylistCommand extends Command
{
    public const COMMAND = 'scan-playlist';

    protected $signature = self::COMMAND.' {playlistId}';
    protected $description = 'Scans a playlist, adds videos from them, and reports ones that are deleted.';

    private $playlistScanner;

    public function __construct(PlaylistScanner $playlistScanner)
    {
        parent::__construct();
        $this->playlistScanner = $playlistScanner;
    }

    public function handle()
    {
        $stopwatchEvent = (new Stopwatch())->start('scan-playlist');
        $this->output->text('Started scanning playlist.');

        $this->playlistScanner->scan($this->argument('playlistId'));

        $this->output->text('Done scanning playlist. '.Profiling::stopwatchToHuman($stopwatchEvent));
    }
}
