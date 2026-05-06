@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'glass-input rounded-xl shadow-sm focus:border-emerald-300 focus:ring-emerald-300/60']) }}>
