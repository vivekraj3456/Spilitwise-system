@extends('layouts.app')

@section('title', 'Groups')

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Groups</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Your groups</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Manage shared spaces, members, and expenses.
                </p>
            </div>
            <a href="{{ route('groups.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-splitwise px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-splitwise-dark hover:shadow-md focus:outline-none focus:ring-2 focus:ring-splitwise focus:ring-offset-2">
                New Group
            </a>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($groups as $group)
                <article class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                <a href="{{ route('groups.show', $group) }}" class="transition hover:text-splitwise-dark">{{ $group->name }}</a>
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">Owner: {{ $group->owner->name }}</p>
                        </div>
                        <span class="rounded-full bg-splitwise-light px-3 py-1 text-xs font-semibold text-splitwise-dark">Active</span>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Members</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $group->users_count }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-4">
                            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">Expenses</p>
                            <p class="mt-1 text-2xl font-bold text-slate-900">{{ $group->expenses_count }}</p>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('groups.show', $group) }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-900">
                            Open
                        </a>
                        <a href="{{ route('groups.expenses.create', $group) }}"
                           class="inline-flex items-center justify-center rounded-xl bg-splitwise px-4 py-2 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-splitwise-dark hover:shadow-md">
                            Add Expense
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-white/20 bg-white/5 p-10 text-center shadow-sm md:col-span-2 xl:col-span-3">
                    <p class="text-sm font-semibold text-slate-900">No groups yet</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first group to begin splitting expenses.</p>
                </div>
            @endforelse
        </section>
    </div>
@endsection

