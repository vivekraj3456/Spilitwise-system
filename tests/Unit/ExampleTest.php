<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\EqualSplitCalculator;
use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    public function test_equal_split_distributes_remainder_cents_by_user_id(): void
    {
        $users = collect([
            new User(['name' => 'Cara', 'email' => 'cara@example.com']),
            new User(['name' => 'Alice', 'email' => 'alice@example.com']),
            new User(['name' => 'Ben', 'email' => 'ben@example.com']),
        ]);

        $users[0]->id = 3;
        $users[1]->id = 1;
        $users[2]->id = 2;

        $shares = (new EqualSplitCalculator)->split(100, $users);

        $this->assertSame([
            1 => 34,
            2 => 33,
            3 => 33,
        ], $shares);
    }
}
