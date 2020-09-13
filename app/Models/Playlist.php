<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Playlist.
 *
 * @property int $id
 * @property string $external_playlist_id
 * @property int $channel_id
 * @property string $title
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \App\Channel $channel
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videosNotRemotelyDeleted
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereExternalPlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Playlist extends Model
{
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }

    public function videosNotRemotelyDeleted(): BelongsToMany
    {
        return $this->belongsToMany(Video::class)->whereNull('known_deleted_at');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
