@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-emerald-300 text-start text-base font-medium text-emerald-100 bg-emerald-400/10 focus:outline-none focus:text-white focus:bg-emerald-400/15 focus:border-emerald-200 transition duration-150 ease-in-out'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-slate-300 hover:text-white hover:bg-white/5 hover:border-white/30 focus:outline-none focus:text-white focus:bg-white/5 focus:border-white/30 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
