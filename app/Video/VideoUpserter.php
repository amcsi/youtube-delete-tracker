<?php

declare(strict_types=1);

namespace App\Models\Video;

use App\Models\Video;
use Carbon\Carbon;

class VideoUpserter
{
    public static function upsertFromPlaylistItem(\Google_Service_YouTube_PlaylistItem $youtubePlaylistItem): ?int
    {
        $snippet = $youtubePlaylistItem->getSnippet();
        $values = [
            'title' => $snippet->title,
            'known_deleted_at' => null, // In case the video had previously been marked deleted upstream.
        ];
        $externalVideoId = $snippet->getResourceId()->videoId;
        if (! $snippet->getThumbnails()) {
            // The video is deleted or private.

            $video = Video::where(['external_video_id' => $externalVideoId])->first();
            if (! $video) {
                // We don't even have a video like that locally; just don't bother with this deleted video.
                return null;
            }

            // Mark the video as deleted.

            if (! $video->known_deleted_at) {
                // Only set the deletion date if it's not set already.
                $video->known_deleted_at = Carbon::now();
                $video->save();
            }

            return $video->id;
        }
        $video = Video::updateOrCreate(
            [
                'external_video_id' => $externalVideoId,
            ],
            $values
        );

        return $video->id;
    }
}
