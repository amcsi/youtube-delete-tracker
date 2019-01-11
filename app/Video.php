<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Video
 *
 * @property int $id
 * @property string $external_video_id
 * @property string $external_channel_id
 * @property string $title
 * @property string|null $known_deleted_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Playlist[] $playlists
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereExternalChannelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereExternalVideoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereKnownDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Video whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Video extends Model
{
    public function playlists(): BelongsToMany
    {
        return $this->belongsToMany(Playlist::class);
    }
}
