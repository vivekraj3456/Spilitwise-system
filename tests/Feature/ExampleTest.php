<?php

namespace Tests\Feature;

use App\Models\Expense;
use App\Models\Group;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }

    public function test_guest_cannot_access_groups(): void
    {
        $this->get('/groups')
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_render_dashboard_groups_and_expenses_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Who owes whom');

        $this->actingAs($user)
            ->get(route('groups.index'))
            ->assertOk()
            ->assertSee('Your groups');

        $this->actingAs($user)
            ->get(route('expenses.index'))
            ->assertOk()
            ->assertSee('All expenses');
    }

    public function test_user_can_create_group_add_member_and_record_equal_split_expense(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create(['email' => 'member@example.com']);

        $this->actingAs($owner)
            ->post(route('groups.store'), ['name' => 'Apartment'])
            ->assertRedirect();

        $group = Group::firstOrFail();

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $owner->id,
        ]);

        $this->actingAs($owner)
            ->post(route('groups.members.store', $group), ['member' => $member->email])
            ->assertRedirect(route('groups.show', $group));

        $this->assertDatabaseHas('group_user', [
            'group_id' => $group->id,
            'user_id' => $member->id,
        ]);

        $this->actingAs($owner)
            ->post(route('groups.expenses.store', $group), [
                'title' => 'Dinner',
                'amount' => '10.01',
                'paid_by_user_id' => $owner->id,
                'expense_date' => '2026-05-04',
            ])
            ->assertRedirect();

        $expense = Expense::firstOrFail();

        $this->assertSame(1001, $expense->amount_cents);
        $this->assertDatabaseHas('expense_splits', [
            'expense_id' => $expense->id,
            'user_id' => $owner->id,
            'amount_cents' => 501,
        ]);
        $this->assertDatabaseHas('expense_splits', [
            'expense_id' => $expense->id,
            'user_id' => $member->id,
            'amount_cents' => 500,
        ]);
    }
}
