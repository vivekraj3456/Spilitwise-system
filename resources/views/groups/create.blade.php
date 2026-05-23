@extends('layouts.app')

@section('title', 'New group')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark">Groups</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-900">New group</h1>
            <p class="mt-3 text-sm leading-6 text-slate-600">Create a shared space for expenses and balances.</p>
        </section>

        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <form class="space-y-5" method="POST" action="{{ route('groups.store') }}" data-loading-form>
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-800">Group name</label>
                    <input id="name"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                              class="mt-2 block w-full rounded-xl border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm focus:border-splitwise focus:ring-splitwise/60">
                    @error('name')
                        <p class="mt-2 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-splitwise px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-splitwise-dark disabled:cursor-not-allowed disabled:opacity-70"
                            data-loading-label="Creating...">
                        Create Group
                    </button>
                    <a href="{{ route('groups.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-900">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection

