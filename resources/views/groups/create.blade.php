@extends('layouts.app')

@section('title', 'New group')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark dark:text-emerald-300">Groups</p>
            <h1 class="mt-2 font-display text-3xl font-bold tracking-tight text-slate-900 dark:text-slate-100">New group</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600 dark:text-slate-400">Create a shared space for expenses and balances.</p>
        </section>

        <section class="card p-6">
            <form class="space-y-5" method="POST" action="{{ route('groups.store') }}" data-loading-form>
                @csrf

                <div>
                          <label for="name" class="block text-sm font-semibold text-slate-800 dark:text-slate-200">Group name</label>
                    <input id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                                        class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500">
                    @error('name')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="btn btn-primary"
                            data-loading-label="Creating...">
                        Create Group
                    </button>
                    <a href="{{ route('groups.index') }}"
                       class="btn btn-secondary">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection

