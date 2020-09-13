<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class DatabaseAccessTest extends TestCase
{
    public function testCanConnect(): void
    {
        self::assertInstanceOf(LengthAwarePaginator::class, User::paginate(1));
    }
}
