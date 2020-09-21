<?php
declare(strict_types=1);

return [
    'youtube' => [
        'idListChunkSize' => (int) env('YOUTUBE_ID_LIST_CHUNK_SIZE', 50),
    ],
    'app' => [
        'forceHttps' => env('FORCE_HTTPS'),
    ],
];
