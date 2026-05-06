@php
    $navItems = [
        [
            'label' => 'Dashboard',
            'href' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h3.75c.621 0 1.125.504 1.125 1.125v6.75C9 20.496 8.496 21 7.875 21h-3.75A1.125 1.125 0 0 1 3 19.875v-6.75ZM15 4.125C15 3.504 15.504 3 16.125 3h3.75C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-3.75A1.125 1.125 0 0 1 15 19.875V4.125ZM10 8.125C10 7.504 10.504 7 11.125 7h1.75C13.496 7 14 7.504 14 8.125v11.75c0 .621-.504 1.125-1.125 1.125h-1.75A1.125 1.125 0 0 1 10 19.875V8.125Z',
        ],
        [
            'label' => 'Groups',
            'href' => route('groups.index'),
            'active' => request()->routeIs('groups.*'),
            'icon' => 'M8.25 6.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM3.75 20.25a8.25 8.25 0 1 1 16.5 0v.75H3.75v-.75ZM16.5 9.75a2.25 2.25 0 1 0 0-4.5 2.25 2.25 0 0 0 0 4.5ZM2.25 18.75a6 6 0 0 1 7.024-5.912A9.725 9.725 0 0 0 1.5 18.75h.75Z',
        ],
        [
            'label' => 'Expenses',
            'href' => route('expenses.index'),
            'active' => request()->routeIs('expenses.*') || request()->routeIs('groups.expenses.*'),
            'icon' => 'M2.25 6.75A2.25 2.25 0 0 1 4.5 4.5h15a2.25 2.25 0 0 1 2.25 2.25v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75Zm3 1.5v1.5h13.5v-1.5H5.25Zm0 4.5v3h4.5v-3h-4.5Z',
        ],
    ];
@endphp

<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 glass-panel lg:flex lg:flex-col">
    <div class="flex h-16 items-center gap-3 border-b border-white/10 px-6">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400 text-sm font-black text-slate-950 shadow-sm shadow-emerald-500/30">
            ES
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-emerald-200">Expense Splitter</p>
            <p class="text-xs text-slate-400">Shared balances</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-6">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
               class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200 {{ $item['active'] ? 'bg-emerald-400/15 text-emerald-100 shadow-sm ring-1 ring-emerald-400/40' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                <svg class="h-5 w-5 flex-none {{ $item['active'] ? 'text-emerald-200' : 'text-slate-400 group-hover:text-white' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="rounded-2xl glass-soft p-4">
            <p class="text-sm font-semibold text-slate-100">{{ Auth::user()->name }}</p>
            <p class="mt-1 truncate text-xs text-slate-400">{{ Auth::user()->email }}</p>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen"
     x-cloak
     class="fixed inset-0 z-50 lg:hidden"
     aria-modal="true"
     role="dialog">
    <div class="fixed inset-0 bg-slate-950/40" @click="sidebarOpen = false"></div>
    <aside class="fixed inset-y-0 left-0 flex w-80 max-w-[86vw] flex-col glass-panel">
        <div class="flex h-16 items-center justify-between border-b border-white/10 px-5">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-400 text-sm font-black text-slate-950">
                    ES
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-emerald-200">Expense Splitter</p>
                    <p class="text-xs text-slate-400">Shared balances</p>
                </div>
            </div>
            <button type="button"
                    class="rounded-xl p-2 text-slate-300 transition hover:bg-white/10 hover:text-white"
                    @click="sidebarOpen = false"
                    aria-label="Close navigation">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 0 1 1.414 0L10 8.586l4.293-4.293a1 1 0 1 1 1.414 1.414L11.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 0 1-1.414-1.414L8.586 10 4.293 5.707a1 1 0 0 1 0-1.414Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <nav class="flex-1 space-y-1 px-4 py-6">
            @foreach ($navItems as $item)
                <a href="{{ $item['href'] }}"
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-semibold transition duration-200 {{ $item['active'] ? 'bg-emerald-400/15 text-emerald-100 ring-1 ring-emerald-400/40' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}">
                    <svg class="h-5 w-5 flex-none {{ $item['active'] ? 'text-emerald-200' : 'text-slate-400 group-hover:text-white' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>
</div>

<header class="sticky top-0 z-30 glass-panel border-b border-white/10 lg:pl-72">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3">
            <button type="button"
                    class="rounded-xl border border-white/10 bg-white/5 p-2 text-slate-200 shadow-sm transition hover:bg-white/10 hover:text-white lg:hidden"
                    @click="sidebarOpen = true"
                    aria-label="Open navigation">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm.75 4.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H2.75Z" clip-rule="evenodd" />
                </svg>
            </button>
            <div>
                <p class="text-sm font-semibold text-slate-100">{{ trim($__env->yieldContent('title')) ?: 'Dashboard' }}</p>
                <p class="hidden text-xs text-slate-400 sm:block">Track shared spending without losing the thread.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('profile.edit') }}" class="hidden rounded-xl px-3 py-2 text-sm font-semibold text-slate-200 transition hover:bg-white/10 hover:text-white sm:inline-flex">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" data-loading-form>
                @csrf
                <button type="submit"
                        class="inline-flex items-center rounded-xl bg-emerald-400 px-4 py-2 text-sm font-semibold text-slate-950 shadow-sm shadow-emerald-500/30 transition duration-200 hover:bg-emerald-300 disabled:cursor-not-allowed disabled:opacity-70"
                        data-loading-label="Logging out...">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
