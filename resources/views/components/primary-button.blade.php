<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl bg-emerald-400 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-950 shadow-sm shadow-emerald-500/30 transition duration-150 hover:bg-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-300/70 focus:ring-offset-0 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
