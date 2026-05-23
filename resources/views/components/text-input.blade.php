@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm rounded-xl shadow-sm focus:border-splitwise focus:ring-splitwise/60']) }}>

