<?php
declare(strict_types=1);

/**
 * @var Video[] $videos
 */

use App\Models\Video;

?>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Playlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                @foreach($videos as $video)
                    <a href="" class="block p-4 border-b-2 border-gray-200 border-solid">{{ $video->title }}</a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
