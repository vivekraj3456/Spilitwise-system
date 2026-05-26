@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-slate-300 bg-white placeholder:text-slate-400 focus:border-splitwise focus:ring-splitwise shadow-sm rounded-xl shadow-sm focus:border-splitwise focus:ring-splitwise/60 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:placeholder:text-slate-500']) }}>

