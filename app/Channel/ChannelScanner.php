<?php

declare(strict_types=1);

namespace App\Models\Channel;

use App\Console\Commands\ScanPlaylistCommand;
use App\Console\Kernel;
use App\ThirdParty\Youtube\Action\PlaylistsByChannelLister;

class ChannelScanner
{
    private $playlistsByChannelLister;
    private $consoleKernel;

    public function __construct(PlaylistsByChannelLister $playlistsByChannelLister, Kernel $consoleKernel)
    {
        $this->playlistsByChannelLister = $playlistsByChannelLister;
        $this->consoleKernel = $consoleKernel;
    }

    public function scan($youtubeChannelId): void
    {
        foreach ($this->playlistsByChannelLister->listAll($youtubeChannelId) as $playlist) {
            $this->consoleKernel->call(ScanPlaylistCommand::COMMAND, ['playlistId' => $playlist->id]);
        }
    }
}
