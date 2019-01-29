<?php
declare(strict_types=1);

namespace App\Video;

use App\Video;

class VideoUpserter
{
    public static function upsertFromPlaylistItem(\Google_Service_YouTube_PlaylistItem $youtubePlaylistItem): int
    {
        $snippet = $youtubePlaylistItem->getSnippet();
        $video = Video::updateOrCreate(
            [
                'external_video_id' => $snippet->getResourceId()->videoId,
            ],
            [
                'title' => $snippet->title,
            ]
        );
        return $video->id;
    }
}