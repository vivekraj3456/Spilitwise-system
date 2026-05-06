<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use App\Services\EqualSplitCalculator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $alice = User::factory()->create([
            'name' => 'Alice Morgan',
            'email' => 'alice@example.com',
            'password' => 'password',
        ]);

        $ben = User::factory()->create([
            'name' => 'Ben Carter',
            'email' => 'ben@example.com',
            'password' => 'password',
        ]);

        $cara = User::factory()->create([
            'name' => 'Cara Singh',
            'email' => 'cara@example.com',
            'password' => 'password',
        ]);

        $group = Group::create([
            'name' => 'Weekend Trip',
            'owner_id' => $alice->id,
        ]);

        $group->users()->attach([$alice->id, $ben->id, $cara->id]);

        $this->createEqualSplitExpense(
            group: $group,
            payer: $alice,
            title: 'Groceries',
            amountCents: 6245,
            date: now()->subDays(2)->toDateString(),
        );

        $this->createEqualSplitExpense(
            group: $group,
            payer: $ben,
            title: 'Fuel',
            amountCents: 3890,
            date: now()->subDay()->toDateString(),
        );
    }

    private function createEqualSplitExpense(
        Group $group,
        User $payer,
        string $title,
        int $amountCents,
        string $date,
    ): void {
        $expense = Expense::create([
            'group_id' => $group->id,
            'paid_by_user_id' => $payer->id,
            'title' => $title,
            'amount_cents' => $amountCents,
            'expense_date' => $date,
        ]);

        $members = $group->users()->orderBy('id')->get();

        foreach (app(EqualSplitCalculator::class)->split($amountCents, $members) as $userId => $shareCents) {
            ExpenseSplit::create([
                'expense_id' => $expense->id,
                'user_id' => $userId,
                'amount_cents' => $shareCents,
            ]);
        }
    }
}
