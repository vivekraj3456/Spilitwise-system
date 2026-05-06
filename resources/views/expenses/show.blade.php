@extends('layouts.app')

@section('title', $expense->title)

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-teal-700">{{ $group->name }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">{{ $expense->title }}</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    Paid by {{ $expense->payer->name }} on {{ $expense->expense_date->format('M j, Y') }}.
                </p>
            </div>
            <a href="{{ route('groups.show', $group) }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-950">
                Back to Group
            </a>
        </section>

        <section class="grid gap-4 md:grid-cols-3">
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm font-medium text-slate-500">Total</p>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm font-medium text-slate-500">Category</p>
                <span class="mt-4 inline-flex rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-700">Equal split</span>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-sm font-medium text-slate-500">Members</p>
                <p class="mt-4 text-3xl font-bold tracking-tight text-slate-950">{{ $expense->splits->count() }}</p>
            </article>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-950">Split breakdown</h2>
                <p class="mt-1 text-sm text-slate-500">Each member's share for this expense.</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Member</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Share</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach ($expense->splits->sortBy('user.name') as $split)
                            <tr class="transition duration-200 hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    <p class="font-semibold text-slate-900">{{ $split->user->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $split->user->email }}</p>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-950">{{ \App\Services\Money::formatCents($split->amount_cents) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
