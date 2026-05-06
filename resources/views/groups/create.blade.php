@extends('layouts.app')

@section('title', 'New group')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <section>
            <p class="text-sm font-semibold uppercase tracking-wide text-teal-700">Groups</p>
            <h1 class="mt-2 text-3xl font-bold tracking-tight text-slate-950">New group</h1>
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
                              class="mt-2 block w-full rounded-xl glass-input focus:border-emerald-300 focus:ring-emerald-300/60">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="submit"
                            class="inline-flex items-center justify-center rounded-xl bg-teal-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition duration-200 hover:bg-teal-700 disabled:cursor-not-allowed disabled:opacity-70"
                            data-loading-label="Creating...">
                        Create Group
                    </button>
                    <a href="{{ route('groups.index') }}"
                       class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition duration-200 hover:bg-slate-50 hover:text-slate-950">
                        Cancel
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection
