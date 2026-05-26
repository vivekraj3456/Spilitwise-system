@extends('layouts.app')

@section('title', 'Add expense')

@section('content')
    <div class="mx-auto max-w-3xl space-y-6">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">{{ $group->name }}</p>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900">Add expense</h1>
                <p class="mt-3 text-sm leading-6 text-slate-600">This expense will be split equally across all current group members.</p>
            </div>
            <a href="{{ route('groups.show', $group) }}"
               class="btn btn-secondary">
                Back to Group
            </a>
        </section>

        <section class="card p-6">
            <form class="space-y-5" method="POST" action="{{ route('groups.expenses.store', $group) }}" enctype="multipart/form-data" data-loading-form>
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

                <div>
                    <label for="category" class="block text-sm font-semibold text-slate-800">Category</label>
                    <select id="category"
                            name="category"
                            class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                        @php
                            $categories = ['General', 'Food', 'Travel', 'Groceries', 'Bills', 'Shopping', 'Rent', 'Entertainment', 'Other'];
                            $selectedCategory = old('category', 'General');
                        @endphp
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" @selected($selectedCategory === $category)>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="rounded-xl bg-slate-50 p-4">
                    <div class="flex flex-wrap gap-2">
                        <span class="badge badge-brand">Equal split</span>
                        <span class="badge badge-neutral bg-white ring-1 ring-slate-200">{{ $members->count() }} members</span>
                    </div>
                </div>

                <div>
                    <label for="receipt_image" class="block text-sm font-semibold text-slate-800">Receipt (optional)</label>
                    <input id="receipt_image"
                           name="receipt_image"
                           type="file"
                           accept="image/*"
                           class="mt-2 block w-full rounded-xl border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:rounded-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-slate-700 hover:file:bg-slate-200 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                    <p class="mt-2 text-sm text-slate-500">PNG, JPG, GIF, SVG, or WEBP up to 5MB.</p>
                    @error('receipt_image')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="btn btn-primary"
                            data-loading-label="Saving...">
                        Save Expense
                    </button>
                    <a href="{{ route('groups.show', $group) }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection

