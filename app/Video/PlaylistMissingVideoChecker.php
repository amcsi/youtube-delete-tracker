<?php
declare(strict_types=1);

namespace App\Video;

use App\Models\Playlist;
use App\Models\Video;
use App\ThirdParty\Youtube\Action\VideoLister;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Psr\Log\LoggerInterface;

/**
 * When listing all items of a playlist, videos that were previously there can be missing. That can either be due to...
 *
 * 1. The video was deleted
 * 2. The video was just removed from the playlist
 *
 * So to figure out which, this class is meant to go through the missing video external IDs and see if they can
 * be found.
 * If so, that means the video was simply removed from the playlist; so the video should be detached.
 * If not, then the video really was deleted (or set to private or something), and it should be marked as deleted.
 */
class PlaylistMissingVideoChecker
{
    private VideoLister $videoLister;
    private $logger;

    public function __construct(VideoLister $videoLister, LoggerInterface $logger)
    {
        $this->videoLister = $videoLister;
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

        $this->logger->info(
            sprintf(
                '%d missing videos from playlist %s (%s)',
                $videosCount,
                $playlist->title,
                $playlist->id
            )
        );

        \DB::transaction(
            function () use ($playlist, $videos) {
                $externalVideoIds = $videos->map->external_video_id->values()->toArray();

                // List all the videos based on the missing external video ids missing from the playlist.
                $foundYoutubeVideos = $this->videoLister->listAll($externalVideoIds);
                $foundExternalVideoIds = [];
                foreach ($foundYoutubeVideos as $foundVideo) {
                    $foundExternalVideoIds[] = $foundVideo->id;
                }

                [$removedFromPlaylistVideos, $deletedVideos] = $videos->partition(
                    fn(Video $video) => in_array($video->external_video_id, $foundExternalVideoIds, true)
                );

                foreach ($removedFromPlaylistVideos as $video) {
                    // This video was only removed from the playlist. Detach it.

                    $this->logger->info(
                        sprintf('Video removed from playlist: %s (%s)', $video->title, $video->external_video_id)
                    );
                    $playlist->videos()->detach($video);
                }

                foreach ($deletedVideos as $video) {
                    // This video is completely missing. Mark it as "deleted".

                    /** @var Video $video */
                    $this->logger->info(sprintf('Missing video: %s (%s)', $video->title, $video->external_video_id));
                    $video->known_deleted_at = Carbon::now();
                    $video->save();
                }
            }
        );
    }
}
