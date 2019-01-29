<?php
declare(strict_types=1);

namespace App\Video;

use App\Video;
use Illuminate\Support\Facades\Log;

class VideoUpserter
{
    public static function upsertFromPlaylistItem(\Google_Service_YouTube_PlaylistItem $youtubePlaylistItem): ?int
    {
        $snippet = $youtubePlaylistItem->getSnippet();
        $videoId = $snippet->getResourceId()->videoId;
        if ($snippet->title === 'Deleted video') {
            // This is unexpected; deleted videos normally just shouldn't show up. Log it, and do not upsert here.
            $message = sprintf('Video with a youtube ID of %s got a title of "Deleted video"', $videoId);
            Log::warning($message);
            return null;
        }
        $video = Video::updateOrCreate(
            [
                'external_video_id' => $snippet->getResourceId()->videoId,
            ],
            [
                'title' => $snippet->title,
                'known_deleted_at' => null, // In case the video had previously been marked deleted upstream.
            ]
        );
        return $video->id;
    }
}
