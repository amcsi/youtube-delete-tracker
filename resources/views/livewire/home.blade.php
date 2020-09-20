<?php
/**
 * @var Playlist[] $playlists
 */

use App\Models\Playlist;

?>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Dashboard') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            @foreach($playlists as $playlist)
                <a
                    href="{{ route('playlists.show', ['playlist' => $playlist->id]) }}"
                    class="block p-4 border-b-2 border-gray-200 border-solid"
                >{{ $playlist->title }}</a>
            @endforeach
        </div>
    </div>
</div>
