<?php

namespace App\Services;

use App\Models\Expense;
use App\Models\ExpenseSplit;
use App\Models\Group;
use App\Models\User;
use Illuminate\Support\Collection;

class BalanceService
{
    /**
     * @return Collection<int, array{user: User, paid_cents: int, owed_cents: int, net_cents: int}>
     */
    public function calculateForGroup(Group $group): Collection
    {
        $members = $group->users()->orderBy('name')->get();

        $paidTotals = Expense::query()
            ->where('group_id', $group->id)
            ->selectRaw('paid_by_user_id, SUM(amount_cents) as total')
            ->groupBy('paid_by_user_id')
            ->pluck('total', 'paid_by_user_id');

        $owedTotals = ExpenseSplit::query()
            ->whereHas('expense', fn ($query) => $query->where('group_id', $group->id))
            ->selectRaw('user_id, SUM(amount_cents) as total')
            ->groupBy('user_id')
            ->pluck('total', 'user_id');

        return $members->map(function ($member) use ($paidTotals, $owedTotals): array {
            $paid = (int) ($paidTotals[$member->id] ?? 0);
            $owed = (int) ($owedTotals[$member->id] ?? 0);

            return [
                'user' => $member,
                'paid_cents' => $paid,
                'owed_cents' => $owed,
                'net_cents' => $paid - $owed,
            ];
        });
    }
}
