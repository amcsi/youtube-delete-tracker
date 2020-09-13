<?php
declare(strict_types=1);

namespace App\Video;

use App\Models\Playlist;
use App\Models\Video;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

class PlaylistMissingVideoChecker
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Gather the videos that are missing from the playlist.
     * They could either be deleted, or just removed from the playlist.
     *
     * @param Video[]|Collection $videos
     *  List of videos that had been expected in the 3rd party response, but were missing.
     */
    public function investigateMissing(Playlist $playlist, Collection $videos): void
    {
        if (!($videosCount = count($videos))) {
            return;
        }

        $this->logger->info(sprintf(
            '%d missing videos from playlist %s (%s)',
            $videosCount,
            $playlist->title,
            $playlist->id
        ));

        foreach ($videos as $video) {
            $this->logger->info(sprintf('Missing video: %s (%s)', $video->title, $video->external_video_id));
        }
    }
}
