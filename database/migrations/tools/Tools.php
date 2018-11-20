<?php
declare(strict_types=1);

namespace database\migrations\tools;

use Illuminate\Database\Schema\Blueprint;

class Tools
{
    public static function timestamps(Blueprint $table)
    {
        $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));
    }
}

