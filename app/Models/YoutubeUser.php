<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class YoutubeUser extends Model implements Authenticatable
{
    use \Illuminate\Auth\Authenticatable;

    public function channel()
    {
        return $this->hasOne(Channel::class, 'external_channel_id', 'external_id');
    }
}
