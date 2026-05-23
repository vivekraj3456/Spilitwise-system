@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
    <div class="space-y-8">
        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Expenses</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">All expenses</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                A consolidated list of transactions from every group you belong to.
            </p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-lg font-semibold text-slate-900">Transactions</h2>
                <p class="mt-1 text-sm text-slate-500">Sorted by newest first.</p>
            </div>

            @if ($expenses->isEmpty())
                <div class="px-6 py-10 text-center">
                    <p class="text-sm font-semibold text-slate-800">No expenses yet</p>
                    <p class="mt-1 text-sm text-slate-500">Add an expense inside a group to see it here.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Expense</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Group</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Paid by</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($expenses as $expense)
                                <tr class="transition duration-200 hover:bg-slate-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('groups.expenses.show', [$expense->group, $expense]) }}" class="font-semibold text-slate-900 transition hover:text-splitwise-dark">{{ $expense->title }}</a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $expense->group->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="rounded-full bg-splitwise-light px-2.5 py-1 text-xs font-semibold text-splitwise-dark">Equal split</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">{{ $expense->payer->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-bold text-slate-900">{{ \App\Services\Money::formatCents($expense->amount_cents) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">{{ $expense->expense_date->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-100 px-6 py-4">
                    {{ $expenses->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection

