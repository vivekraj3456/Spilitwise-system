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
            $net = $paid - $owed;

            // Determine a simple status for UI:
            // - settled: net == 0
            // - gets_back: net > 0 (member should receive money)
            // - owes: net < 0 and paid == 0 (member hasn't paid and owes)
            // - pending: net < 0 and paid > 0 (member paid some but still owes)
            if ($net === 0) {
                $status = 'settled';
                $label = 'Settled';
                $badgeClasses = 'bg-emerald-100 text-emerald-700';
                $summary = 'All settled';
            } elseif ($net > 0) {
                $status = 'gets_back';
                $label = 'Gets back';
                $badgeClasses = 'bg-emerald-100 text-emerald-700';
                $summary = 'Gets back ' . \App\Services\Money::formatCents($net);
            } else { // net < 0
                if ($paid > 0) {
                    $status = 'pending';
                    $label = 'Pending';
                    $badgeClasses = 'bg-amber-100 text-amber-700';
                    $summary = 'Partially paid — still owes ' . \App\Services\Money::formatCents(abs($net));
                } else {
                    $status = 'owes';
                    $label = 'Owes';
                    $badgeClasses = 'bg-red-100 text-red-700';
                    $summary = 'Owes ' . \App\Services\Money::formatCents(abs($net));
                }
            }

            return [
                'user' => $member,
                'paid_cents' => $paid,
                'owed_cents' => $owed,
                'net_cents' => $net,
                'status' => $status,
                'status_label' => $label,
                'status_badge' => $badgeClasses,
                'summary' => $summary,
            ];
        });
    }
}
