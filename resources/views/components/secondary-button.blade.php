<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 shadow-sm transition duration-150 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-splitwise focus:ring-offset-0 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
