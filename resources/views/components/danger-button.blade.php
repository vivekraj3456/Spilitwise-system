<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl bg-rose-500 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm shadow-rose-500/30 transition duration-150 hover:bg-rose-400 focus:outline-none focus:ring-2 focus:ring-rose-300/70 focus:ring-offset-0 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>
