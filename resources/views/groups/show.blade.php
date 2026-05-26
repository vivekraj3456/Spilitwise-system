@extends('layouts.app')

@section('title', $group->name)

@section('content')
    <div class="space-y-8">
        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="bg-gradient-to-r from-slate-950 to-teal-900 px-6 py-7 text-white sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/20">Equal split</span>
                            <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-semibold ring-1 ring-white/20">{{ $group->users->count() }} members</span>
                        </div>
                        <h1 class="mt-4 font-display text-3xl font-bold tracking-tight sm:text-4xl">{{ $group->name }}</h1>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('groups.index') }}"
                           class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 transition duration-200 hover:bg-white/20">
                            All Groups
                        </a>
                        <a href="{{ route('groups.expenses.create', $group) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-teal-400 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-sm transition duration-200 hover:bg-teal-300 hover:shadow-md">
                            Add Expense
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Members</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $group->users->count() }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Expenses</p>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ $group->expenses->count() }}</p>
                </div>
                <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Members</p>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @foreach ($group->users->take(6) as $member)
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">{{ $member->name }}</span>
                        @endforeach
                        @if ($group->users->count() > 6)
                            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-500 ring-1 ring-slate-200">+{{ $group->users->count() - 6 }} more</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="table-shell">
            <div class="card-header">
                <h2 class="font-display text-lg font-semibold text-slate-900">Balances</h2>
                <p class="mt-1 text-sm text-slate-500">Paid, share, and net position for each member.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="table-head">
                        <tr>
                            <th class="table-th">Member</th>
                            <th class="table-th">Paid</th>
                            <th class="table-th">Share</th>
                            <th class="table-th">Net</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($balances as $balance)
                            <tr class="table-tr">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $balance['user']->name }}</div>
                                    <div class="text-sm text-slate-500">{{ $balance['user']->is_guest ? 'No account' : $balance['user']->email }}</div>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ \App\Services\Money::formatCents($balance['paid_cents']) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ \App\Services\Money::formatCents($balance['owed_cents']) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-bold {{ $balance['net_cents'] > 0 ? 'text-splitwise-dark' : ($balance['net_cents'] < 0 ? 'text-danger' : 'text-slate-500') }}">
                                    {{ \App\Services\Money::formatCents($balance['net_cents']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[0.85fr_1fr]">
            <div class="card overflow-hidden">
                <div class="card-header">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Members</h2>
                    <p class="mt-1 text-sm text-slate-500">People included in new equal splits.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach ($group->users as $member)
                        <div class="flex flex-col gap-3 px-6 py-4 transition duration-200 hover:bg-slate-50 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <p class="font-semibold text-slate-900">{{ $member->name }}</p>
                                    @if ($member->id === $group->owner_id)
                                        <span class="badge badge-neutral">Owner</span>
                                    @elseif ($member->is_guest)
                                        <span class="badge badge-neutral">Guest</span>
                                    @endif
                                </div>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $member->is_guest ? 'No account' : $member->email }}
                                </p>
                            </div>
                            @if (auth()->id() === $group->owner_id && $member->id !== $group->owner_id)
                                <form method="POST" action="{{ route('groups.members.destroy', [$group, $member]) }}" data-loading-form>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger px-3 py-2"
                                            data-loading-label="Removing...">
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            @if (auth()->id() === $group->owner_id)
                <div class="card p-6">
                    <h2 class="font-display text-lg font-semibold text-slate-900">Add member</h2>
                    <p class="mt-1 text-sm text-slate-500">Add people to this group and split expenses together.</p>
                    <form class="mt-5 space-y-4" method="POST" action="{{ route('groups.members.store', $group) }}" data-loading-form>
                        @csrf
                        <div>
                            <label for="member" class="block text-sm font-semibold text-slate-800">Member name or email</label>
                            <input id="member"
                                   name="member"
                                   type="text"
                                   value="{{ old('member') }}"
                                   placeholder="Enter member name or email"
                                   required
                                class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                            <p class="mt-2 text-sm text-slate-500">If the email matches a registered user, their account will be added. Otherwise we'll add a guest member.</p>
                            @error('member')
                                <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="btn btn-primary"
                                data-loading-label="Adding...">
                            Add Member
                        </button>
                    </form>
                </div>
            @endif
        </section>

        <section class="table-shell">
            <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-display text-lg font-semibold text-slate-900">Expenses</h2>
                    <p class="mt-1 text-sm text-slate-500">Shared costs recorded for this group.</p>
                </div>
                <a href="{{ route('groups.expenses.create', $group) }}"
                   class="btn btn-primary">
                    Add Expense
                </a>
            </div>

            @if ($group->expenses->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-800">No expenses yet</p>
                    <p class="mt-1 text-sm text-slate-500">Add the first expense to calculate member balances.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="table-head">
                            <tr>
                                <th class="table-th">Expense</th>
                                <th class="table-th">Category</th>
                                <th class="table-th">Paid by</th>
                                <th class="table-th">Amount</th>
                                <th class="table-th">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($group->expenses as $expense)
                                <tr class="table-tr">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('groups.expenses.show', [$group, $expense]) }}" class="font-semibold text-slate-900 transition hover:text-splitwise-dark">{{ $expense->title }}</a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="badge badge-neutral">{{ $expense->category ?: 'General' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $expense->payer->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $expense->expense_date->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection

