<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Channel
 *
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Playlist[] $playlists
 */
class Channel extends Model
{
    protected $fillable = [
        'external_channel_id',
        'name',
    ];

    public function playlists(): HasMany
    {
        return $this->hasMany(Playlist::class);
    }
}
