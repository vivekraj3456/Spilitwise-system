@extends('layouts.app')

@section('title', 'Expenses')

@section('content')
    <div class="space-y-8">
        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Expenses</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">All expenses</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                A consolidated list of transactions from every group you belong to.
            </p>
        </section>

        <section class="table-shell">
            <div class="card-header">
                <h2 class="font-display text-lg font-semibold text-slate-900">Transactions</h2>
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
                        <thead class="table-head">
                            <tr>
                                <th class="table-th">Expense</th>
                                <th class="table-th">Group</th>
                                <th class="table-th">Category</th>
                                <th class="table-th">Paid by</th>
                                <th class="table-th">Amount</th>
                                <th class="table-th">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($expenses as $expense)
                                <tr class="table-tr">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('groups.expenses.show', [$expense->group, $expense]) }}" class="font-semibold text-slate-900 transition hover:text-splitwise-dark focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-splitwise focus-visible:ring-offset-2 rounded-md">{{ $expense->title }}</a>
                                    </td>
                                    <td class="table-td">{{ $expense->group->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="badge badge-neutral">{{ $expense->category ?: 'General' }}</span>
                                    </td>
                                    <td class="table-td">{{ $expense->payer->name }}</td>
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

