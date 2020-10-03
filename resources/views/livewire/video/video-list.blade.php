<?php
declare(strict_types=1);

/**
 * @var Video[]|LengthAwarePaginator $videos
 */

use App\Models\Video;
use Illuminate\Pagination\LengthAwarePaginator;

?>

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Deleted videos') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <x-jet-input wire:model.debounce.250ms="search" placeholder="Search" />

        {!! $videos->links() !!}

        <div class="h-4"></div>

        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Name
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Youtube ID
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Date deleted
                                        </th>
                                        <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                            Playlists
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">

                                    @foreach($videos as $video)
                                        <tr class="{{ $video->known_deleted_at ? 'bg-red-50' : '' }}">
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm leading-5 text-gray-500">
                                                            {{ $video->title }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                <div class="text-sm leading-5 text-gray-900">{{$video->external_video_id}}
                                                    @if(!$video->known_deleted_at)
                                                        <a
                                                            href="https://www.youtube.com/watch?v={{ rawurlencode($video->external_video_id) }}"
                                                            target="_blank"
                                                            rel="nofollow noopener"
                                                            title="Open video in new tab"
                                                        >
                                                            🔗
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                @if ($video->known_deleted_at)
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                  {{ $video->known_deleted_at }}
                                                </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-no-wrap">
                                                @foreach ($video->playlists as $playlist)
                                                    <div>
                                                        <a href="{{ route('playlists.show', ['playlist' => $playlist->id]) }}">{{ $playlist->title }}</a>
                                                    </div>
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="h-4"></div>

        {!! $videos->links() !!}
    </div>
</div>
