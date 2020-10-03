<?php
declare(strict_types=1);

namespace App\Database;

class DatabaseUtils
{
    public static function escapeLike($text): string
    {
        return sprintf('%%%s%%', addcslashes((string) $text, '%_'));
    }
}
