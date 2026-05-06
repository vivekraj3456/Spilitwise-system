@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Overview</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">Dashboard</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Current totals, settlements, and recent activity across your groups.
                </p>
            </div>
            <a href="{{ route('groups.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-teal-700 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                Add Group
            </a>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">Total expenses</p>
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">All groups</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950">
                    {{ \App\Services\Money::formatCents($totalExpensesCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">{{ $recentExpenses->count() }} recent transactions loaded</p>
            </article>

            <article class="rounded-xl border border-red-100 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">You owe</p>
                    <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-semibold text-red-700">Outgoing</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-red-700">
                    {{ \App\Services\Money::formatCents($youOweCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">Based on your negative group balances.</p>
            </article>

            <article class="rounded-xl border border-emerald-100 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex items-center justify-between gap-4">
                    <p class="text-sm font-medium text-slate-500">You are owed</p>
                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Incoming</span>
                </div>
                <p class="mt-4 text-3xl font-bold tracking-tight text-emerald-700">
                    {{ \App\Services\Money::formatCents($youAreOwedCents) }}
                </p>
                <p class="mt-2 text-sm text-slate-500">Based on your positive group balances.</p>
            </article>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1fr_0.9fr]">
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-950">Who owes whom</h2>
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
                            <span class="inline-flex w-fit rounded-full bg-amber-50 px-3 py-1 text-sm font-bold text-amber-700">
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

            <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h2 class="text-lg font-semibold text-slate-950">Group snapshot</h2>
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
                                        {{ $summary['group']->users_count }} members · {{ $summary['group']->expenses_count }} expenses
                                    </p>
                                </div>
                                <span class="text-sm font-bold {{ $summary['net_cents'] > 0 ? 'text-emerald-700' : ($summary['net_cents'] < 0 ? 'text-red-700' : 'text-slate-500') }}">
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

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent transactions</h2>
                    <p class="mt-1 text-sm text-slate-500">Latest expenses recorded in your groups.</p>
                </div>
                <a href="{{ route('expenses.index') }}" class="text-sm font-semibold text-teal-700 transition hover:text-teal-900">
                    View all
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($recentExpenses as $expense)
                    <a href="{{ route('groups.expenses.show', [$expense->group, $expense]) }}"
                       class="flex flex-col gap-3 px-6 py-4 transition duration-200 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="font-semibold text-slate-900">{{ $expense->title }}</p>
                                <span class="rounded-full bg-teal-50 px-2.5 py-1 text-xs font-semibold text-teal-700">Equal split</span>
                            </div>
                            <p class="mt-1 text-sm text-slate-500">
                                {{ $expense->group->name }} · paid by {{ $expense->payer->name }} · {{ $expense->expense_date->format('M j, Y') }}
                            </p>
                        </div>
                        <span class="text-base font-bold text-slate-950">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</span>
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
