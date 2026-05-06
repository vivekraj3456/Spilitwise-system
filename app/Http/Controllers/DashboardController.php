<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Services\BalanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request, BalanceService $balanceService): View
    {
        $user = $request->user();

        $groups = $user->groups()
            ->withCount(['users', 'expenses'])
            ->latest('groups.created_at')
            ->get();

        $groupSummaries = $groups->map(function ($group) use ($user, $balanceService): array {
            $balances = $balanceService->calculateForGroup($group);
            $userBalance = $balances->first(fn ($balance) => $balance['user']->id === $user->id);

            return [
                'group' => $group,
                'balances' => $balances,
                'net_cents' => $userBalance['net_cents'] ?? 0,
            ];
        });

        $visibleExpenses = Expense::query()
            ->whereHas('group.users', fn ($query) => $query->whereKey($user->id));

        $recentExpenses = Expense::query()
            ->whereHas('group.users', fn ($query) => $query->whereKey($user->id))
            ->with(['group', 'payer'])
            ->latest('expense_date')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard.index', [
            'groupSummaries' => $groupSummaries,
            'recentExpenses' => $recentExpenses,
            'totalExpensesCents' => (int) $visibleExpenses->sum('amount_cents'),
            'totalNetCents' => $groupSummaries->sum('net_cents'),
            'youOweCents' => abs($groupSummaries->where('net_cents', '<', 0)->sum('net_cents')),
            'youAreOwedCents' => $groupSummaries->where('net_cents', '>', 0)->sum('net_cents'),
            'settlements' => $groupSummaries
                ->flatMap(fn ($summary) => $this->settlementsForGroup($summary['group'], $summary['balances']))
                ->take(8)
                ->values(),
        ]);
    }

    private function settlementsForGroup($group, $balances)
    {
        $debtors = $balances
            ->filter(fn ($balance) => $balance['net_cents'] < 0)
            ->map(fn ($balance) => [
                'user' => $balance['user'],
                'amount_cents' => abs($balance['net_cents']),
            ])
            ->values();

        $creditors = $balances
            ->filter(fn ($balance) => $balance['net_cents'] > 0)
            ->map(fn ($balance) => [
                'user' => $balance['user'],
                'amount_cents' => $balance['net_cents'],
            ])
            ->values();

        $settlements = collect();
        $debtorIndex = 0;
        $creditorIndex = 0;

        while ($debtorIndex < $debtors->count() && $creditorIndex < $creditors->count()) {
            $debtor = $debtors[$debtorIndex];
            $creditor = $creditors[$creditorIndex];
            $amount = min($debtor['amount_cents'], $creditor['amount_cents']);

            if ($amount > 0) {
                $settlements->push([
                    'group' => $group,
                    'from' => $debtor['user'],
                    'to' => $creditor['user'],
                    'amount_cents' => $amount,
                ]);
            }

            $debtor['amount_cents'] -= $amount;
            $creditor['amount_cents'] -= $amount;
            $debtors[$debtorIndex] = $debtor;
            $creditors[$creditorIndex] = $creditor;

            if ($debtor['amount_cents'] === 0) {
                $debtorIndex++;
            }

            if ($creditor['amount_cents'] === 0) {
                $creditorIndex++;
            }
        }

        return $settlements;
    }
}
