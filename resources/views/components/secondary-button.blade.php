<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center rounded-xl border border-white/15 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-100 shadow-sm transition duration-150 hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/30 focus:ring-offset-0 disabled:opacity-50']) }}>
    {{ $slot }}
</button>
