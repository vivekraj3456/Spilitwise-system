@extends('layouts.app')

@section('title', 'Groups')

@section('content')
    <div class="space-y-8">
        <section class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Groups</p>
                <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Your groups</h1>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Manage shared spaces, members, and expenses.
                </p>
            </div>
            <a href="{{ route('groups.create') }}"
               class="btn btn-primary">
                New Group
            </a>
        </section>

        <section class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @forelse ($groups as $group)
                <article class="card card-hover p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">
                                <a href="{{ route('groups.show', $group) }}" class="transition hover:text-splitwise-dark">{{ $group->name }}</a>
                            </h2>
                            <p class="mt-1 text-sm text-slate-500">Owner: {{ $group->owner->name }}</p>
                        </div>
                        <span class="badge badge-brand">Active</span>
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
                           class="btn btn-secondary px-4 py-2">
                            Open
                        </a>
                        <a href="{{ route('groups.expenses.create', $group) }}"
                           class="btn btn-primary px-4 py-2">
                            Add Expense
                        </a>
                    </div>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-12 text-center shadow-sm md:col-span-2 xl:col-span-3">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-splitwise-light text-splitwise">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M8.25 6.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM3.75 20.25a8.25 8.25 0 1 1 16.5 0v.75H3.75v-.75Z" />
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-900">No groups yet</p>
                    <p class="mt-1 text-sm text-slate-500">Create your first group to begin splitting expenses.</p>
                    <a href="{{ route('groups.create') }}" class="btn btn-primary mt-6">Create a group</a>
                </div>
            @endforelse
        </section>
    </div>
@endsection

