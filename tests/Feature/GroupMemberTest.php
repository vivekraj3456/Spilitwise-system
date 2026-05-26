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

    public function test_owner_can_add_existing_registered_user_by_email(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create([
            'email' => 'member@example.com',
        ]);

        $group = Group::create([
            'name' => 'Studio',
            'owner_id' => $owner->id,
        ]);

        $group->users()->attach($owner->id);

        $this->actingAs($owner)
            ->post(route('groups.members.store', $group), [
                'member' => 'member@example.com',
            ])
            ->assertRedirect(route('groups.show', $group));

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
        ]);
    }

    public function test_owner_can_add_guest_member_by_name_without_errors(): void
    {
        $owner = User::factory()->create();

        $group = Group::create([
            'name' => 'Home',
            'owner_id' => $owner->id,
        ]);

        $group->users()->attach($owner->id);

        $this->actingAs($owner)
            ->post(route('groups.members.store', $group), [
                'member' => 'Mom',
            ])
            ->assertRedirect(route('groups.show', $group));

        $this->assertDatabaseHas('users', [
            'name' => 'Mom',
            'is_guest' => 1,
        ]);

        $guest = User::where('name', 'Mom')->where('is_guest', true)->firstOrFail();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $guest->id,
        ]);
    }

    public function test_adding_same_guest_name_twice_does_not_create_duplicate_guest_users(): void
    {
        $owner = User::factory()->create();

        $group = Group::create([
            'name' => 'Roommates',
            'owner_id' => $owner->id,
        ]);

        $group->users()->attach($owner->id);

        $this->actingAs($owner)->post(route('groups.members.store', $group), ['member' => 'Roommate']);
        $this->actingAs($owner)->post(route('groups.members.store', $group), ['member' => 'Roommate']);

        $this->assertSame(1, User::where('is_guest', true)->where('name', 'Roommate')->count());

        $guest = User::where('is_guest', true)->where('name', 'Roommate')->firstOrFail();
        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $guest->id,
        ]);
    }

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
