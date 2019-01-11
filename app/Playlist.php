<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Playlist
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist query()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Video[] $videos
 * @property int $id
 * @property string $external_playlist_id
 * @property string $external_channel_id
 * @property string $title
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereExternalChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereExternalPlaylistId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereUpdatedAt($value)
 * @property-read \App\Channel $channel
 * @property int $channel_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Playlist whereChannelId($value)
 */
class Playlist extends Model
{
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }
}
