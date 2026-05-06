<?php

namespace Tests\Unit;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use App\Services\BalanceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BalanceServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_for_group_returns_balances(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $group = Group::create([
            'name' => 'Road Trip',
            'owner_id' => $owner->id,
        ]);

        $group->users()->attach([$owner->id, $member->id]);

        $expense = Expense::create([
            'group_id' => $group->id,
            'paid_by_user_id' => $owner->id,
            'title' => 'Gas',
            'amount_cents' => 5000,
            'expense_date' => now()->toDateString(),
        ]);

        ExpenseSplit::create([
            'expense_id' => $expense->id,
            'user_id' => $owner->id,
            'amount_cents' => 2500,
        ]);

        ExpenseSplit::create([
            'expense_id' => $expense->id,
            'user_id' => $member->id,
            'amount_cents' => 2500,
        ]);

        $balances = app(BalanceService::class)->calculateForGroup($group);

        $ownerBalance = $balances->first(fn ($balance) => $balance['user']->id === $owner->id);
        $memberBalance = $balances->first(fn ($balance) => $balance['user']->id === $member->id);

        $this->assertSame(2500, $ownerBalance['net_cents']);
        $this->assertSame(-2500, $memberBalance['net_cents']);
        $this->assertSame(0, $balances->sum('net_cents'));
    }
}
