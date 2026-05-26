@extends('layouts.app')

@section('title', $expense->title)

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark dark:text-emerald-300">{{ $group->name }}</p>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-slate-100">{{ $expense->title }}</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">
                    Paid by {{ $expense->payer->name }} on {{ $expense->expense_date->format('M j, Y') }}.
                </p>
            </div>
            <a href="{{ route('groups.show', $group) }}"
               class="btn btn-secondary">
                Back to Group
            </a>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="card card-hover p-6">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total</p>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</p>
            </article>
            <article class="card card-hover p-6">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Category</p>
                <span class="badge badge-neutral mt-4 text-sm">{{ $expense->category ?: 'General' }}</span>
            </article>
            <article class="card card-hover p-6">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Members</p>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">{{ $expense->splits->count() }}</p>
            </article>
        </section>

        <section class="table-shell">
            <div class="card-header">
                <h2 class="font-display text-lg font-semibold text-slate-900 dark:text-slate-100">Split breakdown</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Each member's share for this expense.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="table-head">
                        <tr>
                            <th class="table-th">Member</th>
                            <th class="table-th">Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white dark:divide-slate-800 dark:bg-slate-900/70">
                        @foreach ($expense->splits->sortBy('user.name') as $split)
                            <tr class="table-tr">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="font-semibold text-slate-900 dark:text-slate-100">{{ $split->user->name }}</p>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ $split->user->email }}</p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900 dark:text-slate-100">{{ \App\Services\Money::formatCents($split->amount_cents) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="card overflow-hidden">
            <div class="card-header">
                <h2 class="font-display text-lg font-semibold text-slate-900 dark:text-slate-100">Receipt</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Uploaded proof of payment (if provided).</p>
            </div>

            <div class="px-6 py-6">
                @if ($expense->receipt_image)
                    <a href="{{ asset('storage/' . $expense->receipt_image) }}" target="_blank" rel="noopener" class="btn btn-secondary">
                        Open receipt
                    </a>

                    <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-slate-50 dark:border-slate-800 dark:bg-slate-900/70">
                        <img src="{{ asset('storage/' . $expense->receipt_image) }}"
                             alt="Receipt for {{ $expense->title }}"
                             class="h-auto w-full max-w-3xl">
                    </div>
                @else
                    <p class="text-sm text-slate-600 dark:text-slate-400">No receipt uploaded for this expense.</p>
                @endif
            </div>
        </section>
    </div>
@endsection

