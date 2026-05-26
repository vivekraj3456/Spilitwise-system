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

<aside class="fixed inset-y-0 left-0 z-40 hidden w-72 bg-white border-r border-slate-200 lg:flex lg:flex-col shadow-sm dark:bg-slate-950 dark:border-slate-800">
    <div class="flex h-16 items-center gap-3 border-b border-slate-100 px-6 dark:border-slate-800">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-splitwise text-sm font-black text-white shadow-sm">
            ES
        </div>
        <div>
            <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark dark:text-emerald-300">Expense Splitter</p>
            <p class="text-xs text-slate-500 dark:text-slate-400">Shared balances</p>
        </div>
    </div>

    <nav class="flex-1 space-y-1 px-4 py-6">
        @foreach ($navItems as $item)
            <a href="{{ $item['href'] }}"
               class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition duration-200 {{ $item['active'] ? 'bg-splitwise-light text-splitwise-dark font-semibold dark:bg-emerald-500/15 dark:text-emerald-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white' }}">
                <svg class="h-5 w-5 flex-none {{ $item['active'] ? 'text-splitwise dark:text-emerald-300' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-200' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path d="{{ $item['icon'] }}" />
                </svg>
                {{ $item['label'] }}
            </a>
        @endforeach
    </nav>

    <div class="border-t border-slate-100 p-4 dark:border-slate-800">
        <div class="rounded-xl bg-slate-50 p-4 border border-slate-100 dark:bg-slate-900/70 dark:border-slate-800">
            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</p>
            <p class="mt-1 truncate text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
        </div>
    </div>
</aside>

<div x-show="sidebarOpen"
     x-cloak
     class="fixed inset-0 z-50 lg:hidden"
     aria-modal="true"
     role="dialog">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    <aside class="fixed inset-y-0 left-0 flex w-80 max-w-[86vw] flex-col bg-white shadow-xl dark:bg-slate-950">
        <div class="flex h-16 items-center justify-between border-b border-slate-100 px-5 dark:border-slate-800">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-splitwise text-sm font-black text-white">
                    ES
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-wide text-splitwise-dark dark:text-emerald-300">Expense Splitter</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Shared balances</p>
                </div>
            </div>
            <button type="button"
                    class="rounded-xl p-2 text-slate-400 transition hover:bg-slate-100 hover:text-slate-600 dark:text-slate-400 dark:hover:bg-slate-900 dark:hover:text-slate-200"
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
                   class="group flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition duration-200 {{ $item['active'] ? 'bg-splitwise-light text-splitwise-dark font-semibold dark:bg-emerald-500/15 dark:text-emerald-200' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white' }}">
                    <svg class="h-5 w-5 flex-none {{ $item['active'] ? 'text-splitwise dark:text-emerald-300' : 'text-slate-400 group-hover:text-slate-600 dark:text-slate-500 dark:group-hover:text-slate-200' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="{{ $item['icon'] }}" />
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>
</div>

<header class="sticky top-0 z-30 bg-white border-b border-slate-200 lg:pl-72 shadow-sm dark:bg-slate-950 dark:border-slate-800">
    <div class="flex h-16 items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <button type="button"
                    class="rounded-xl border border-slate-200 bg-white p-2 text-slate-500 shadow-sm transition hover:bg-slate-50 hover:text-slate-700 lg:hidden dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-slate-100"
                    @click="sidebarOpen = true"
                    aria-label="Open navigation">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M2 4.75A.75.75 0 0 1 2.75 4h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 4.75ZM2 10a.75.75 0 0 1 .75-.75h14.5a.75.75 0 0 1 0 1.5H2.75A.75.75 0 0 1 2 10Zm.75 4.5a.75.75 0 0 0 0 1.5h14.5a.75.75 0 0 0 0-1.5H2.75Z" clip-rule="evenodd" />
                </svg>
            </button>
            <div class="flex-1 lg:flex-none">
                <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">{{ trim($__env->yieldContent('title')) ?: 'Dashboard' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="button"
                    class="rounded-xl border border-slate-200 bg-white p-2 text-slate-600 shadow-sm transition hover:bg-slate-50 hover:text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800 dark:hover:text-slate-100"
                    @click="$store.theme.toggle()"
                    aria-label="Toggle dark mode">
                <span x-cloak x-show="!$store.theme.dark" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M21.752 15.002A9.718 9.718 0 0 1 12 21.75 9.75 9.75 0 0 1 11.002 2.25a.75.75 0 0 1 .783.987A7.5 7.5 0 0 0 21.752 15.002Z" />
                    </svg>
                </span>
                <span x-cloak x-show="$store.theme.dark" aria-hidden="true">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 3.75a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5A.75.75 0 0 1 12 3.75Zm0 16.5a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1-.75-.75Zm8.25-8.25a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1-.75-.75Zm-16.5 0a.75.75 0 0 1 .75-.75h.5a.75.75 0 0 1 0 1.5h-.5a.75.75 0 0 1-.75-.75Zm12.97-5.47a.75.75 0 0 1 1.06 0l.353.353a.75.75 0 0 1-1.06 1.06l-.353-.353a.75.75 0 0 1 0-1.06Zm-10.606 10.606a.75.75 0 0 1 1.06 0l.353.353a.75.75 0 0 1-1.06 1.06l-.353-.353a.75.75 0 0 1 0-1.06Zm10.606 1.06a.75.75 0 0 1 1.06-1.06l.353.353a.75.75 0 0 1-1.06 1.06l-.353-.353Zm-10.606-10.606a.75.75 0 0 1 1.06-1.06l.353.353a.75.75 0 0 1-1.06 1.06l-.353-.353Zm5.886.566a4.5 4.5 0 1 1-4.5 4.5 4.505 4.505 0 0 1 4.5-4.5Z" />
                    </svg>
                </span>
            </button>
            <a href="{{ route('profile.edit') }}" class="hidden rounded-xl px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50 hover:text-slate-900 sm:inline-flex dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-slate-100">
                Profile
            </a>
            <form method="POST" action="{{ route('logout') }}" data-loading-form>
                @csrf
                <button type="submit"
                        class="btn btn-primary"
                        data-loading-label="Logging out...">
                    Logout
                </button>
            </form>
        </div>
    </div>
</header>
