@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Overview</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Dashboard</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Current totals, settlements, and recent activity across your groups.
                </p>
            </div>
            <a href="{{ route('groups.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-splitwise px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-splitwise-dark hover:shadow-md focus:outline-none focus:ring-2 focus:ring-splitwise focus:ring-offset-2">
                Add Group
            </a>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">Total expenses</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">All groups</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900">
                    {{ \App\Services\Money::formatCents($totalExpensesCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">{{ $recentExpenses->count() }} recent transactions loaded</p>
            </article>

            <article class="rounded-xl border border-danger-light bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">You owe</p>
                    <span class="rounded-full bg-danger-light px-3 py-1 text-xs font-semibold text-danger">Outgoing</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-danger">
                    {{ \App\Services\Money::formatCents($youOweCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">Based on your negative group balances.</p>
            </article>

            <article class="rounded-xl border border-splitwise-light bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">You are owed</p>
                    <span class="rounded-full bg-splitwise-light px-3 py-1 text-xs font-semibold text-splitwise-dark">Incoming</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-splitwise-dark">
                    {{ \App\Services\Money::formatCents($youAreOwedCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">Based on your positive group balances.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Who owes whom</h2>
                    <p class="mt-1 text-sm text-slate-500">Suggested settlement paths from current equal-split balances.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($settlements as $settlement)
                        <div class="flex flex-col gap-3 px-6 py-4 transition duration-200 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">
                                    {{ $settlement['from']->name }} owes {{ $settlement['to']->name }}
                                </p>
                                <p class="mt-1 text-sm text-slate-500">{{ $settlement['group']->name }}</p>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-orange-50 px-3 py-1 text-sm font-bold text-orange-600 border border-orange-100">
                                {{ \App\Services\Money::formatCents($settlement['amount_cents']) }}
                            </span>
                        </div>
                    @empty
                        <div class="px-6 py-10 text-center">
                            <p class="text-sm font-semibold text-slate-800">No open settlements</p>
                            <p class="mt-1 text-sm text-slate-500">Your groups are balanced or do not have expenses yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                <div class="border-b border-slate-100 bg-slate-50 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-900">Group snapshot</h2>
                    <p class="mt-1 text-sm text-slate-500">Your net balance by group.</p>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($groupSummaries as $summary)
                        <a href="{{ route('groups.show', $summary['group']) }}"
                           class="block px-6 py-4 transition duration-200 hover:bg-slate-50">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $summary['group']->name }}</p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        {{ $summary['group']->users_count }} members &middot; {{ $summary['group']->expenses_count }} expenses
                                    </p>
                                </div>
                                <span class="text-sm font-bold {{ $summary['net_cents'] > 0 ? 'text-splitwise-dark' : ($summary['net_cents'] < 0 ? 'text-danger' : 'text-slate-500') }}">
                                    {{ \App\Services\Money::formatCents($summary['net_cents']) }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <div class="px-6 py-10 text-center text-sm text-slate-500">
                            No groups yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="flex flex-col gap-3 border-b border-slate-100 bg-slate-50 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">Recent transactions</h2>
                    <p class="mt-1 text-sm text-slate-500">Latest expenses recorded in your groups.</p>
                </div>
                <a href="{{ route('expenses.index') }}" class="text-sm font-semibold text-splitwise transition hover:text-splitwise-dark">
                    View all
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($recentExpenses as $expense)
                    <a href="{{ route('groups.expenses.show', [$expense->group, $expense]) }}"
                       class="flex flex-col gap-3 px-6 py-4 transition duration-200 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-medium text-slate-900">{{ $expense->title }}</p>
                                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600 uppercase tracking-wider">Equal split</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">
                                <span class="font-medium text-slate-600">{{ $expense->group->name }}</span> &middot; paid by {{ $expense->payer->name }} &middot; {{ $expense->expense_date->format('M j, Y') }}
                            </p>
                        </div>
                        <span class="text-base font-bold text-slate-900">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</span>
                    </a>
                @empty
                    <div class="px-6 py-10 text-center">
                        <p class="text-sm font-semibold text-slate-800">No transactions yet</p>
                        <p class="mt-1 text-sm text-slate-500">Create a group and add an expense to start tracking balances.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
