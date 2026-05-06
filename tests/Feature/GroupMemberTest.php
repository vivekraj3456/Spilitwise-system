<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_cannot_remove_member_with_expense_history(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create();

        $group = Group::create([
            'name' => 'Studio',
            'owner_id' => $owner->id,
        ]);

        $group->users()->attach([$owner->id, $member->id]);

        $expense = Expense::create([
            'group_id' => $group->id,
            'paid_by_user_id' => $owner->id,
            'title' => 'Utilities',
            'amount_cents' => 8000,
            'expense_date' => now()->toDateString(),
        ]);

        ExpenseSplit::create([
            'expense_id' => $expense->id,
            'user_id' => $owner->id,
            'amount_cents' => 4000,
        ]);

        ExpenseSplit::create([
            'expense_id' => $expense->id,
            'user_id' => $member->id,
            'amount_cents' => 4000,
        ]);

        $this->actingAs($owner)
            ->delete(route('groups.members.destroy', [$group, $member]))
            ->assertSessionHasErrors('member');

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
        ]);
    }
}
