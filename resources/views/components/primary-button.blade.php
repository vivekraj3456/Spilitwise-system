<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center rounded-xl bg-splitwise px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white shadow-sm shadow-splitwise/30 transition duration-150 hover:bg-splitwise-dark focus:outline-none focus:ring-2 focus:ring-splitwise/70 focus:ring-offset-0 disabled:cursor-not-allowed disabled:opacity-60']) }}>
    {{ $slot }}
</button>

