@extends('layouts.app')

@section('title', 'Add expense')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">{{ $group->name }}</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">Add expense</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">This expense will be split equally across all current group members.</p>
            </div>
            <a href="{{ route('groups.show', $group) }}"
               class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-900">
                Back to Group
            </a>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="space-y-5" method="POST" action="{{ route('groups.expenses.store', $group) }}" data-loading-form>
                @csrf

                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-800">Title</label>
                    <input id="title"
                           name="title"
                           value="{{ old('title') }}"
                           required
                           autofocus
                              class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                    @error('title')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="amount" class="block text-sm font-semibold text-slate-800">Amount</label>
                        <input id="amount"
                               name="amount"
                               inputmode="decimal"
                               placeholder="1,250.00"
                               value="{{ old('amount') }}"
                               required
                               class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                        @error('amount')
                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="expense_date" class="block text-sm font-semibold text-slate-800">Date</label>
                        <input id="expense_date"
                               name="expense_date"
                               type="date"
                               value="{{ old('expense_date', now()->toDateString()) }}"
                               required
                               class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                        @error('expense_date')
                            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="paid_by_user_id" class="block text-sm font-semibold text-slate-800">Paid by</label>
                    <select id="paid_by_user_id"
                            name="paid_by_user_id"
                            required
                            class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                        @foreach ($members as $member)
                            <option value="{{ $member->id }}" @selected((int) old('paid_by_user_id', auth()->id()) === $member->id)>
                                {{ $member->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('paid_by_user_id')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="rounded-full bg-splitwise-light px-3 py-1 text-xs font-semibold text-splitwise-dark">Equal split</span>
                        <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold text-slate-600 ring-1 ring-slate-200">{{ $members->count() }} members</span>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-splitwise px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-splitwise-dark disabled:cursor-not-allowed disabled:opacity-70"
                            data-loading-label="Saving...">
                        Save Expense
                    </button>
                    <a href="{{ route('groups.show', $group) }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-900">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection

