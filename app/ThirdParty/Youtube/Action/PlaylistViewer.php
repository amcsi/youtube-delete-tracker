<?php
declare(strict_types=1);

namespace App\ThirdParty\Youtube\Action;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PlaylistViewer
{
    private $youtube;

    public function __construct(\Google_Service_YouTube $youtube)
    {
        $this->youtube = $youtube;
    }

    public function view(string $playlistId): \Google_Service_YouTube_Playlist
    {
        $playlist = $this->youtube->playlists->listPlaylists(
            'snippet',
            ['id' => $playlistId, 'maxResults' => 1]
        )->current();
        if (!$playlist) {
            throw new NotFoundHttpException("Could not find playlist with an ID of $playlistId");
        }
        return $playlist;
    }
}
