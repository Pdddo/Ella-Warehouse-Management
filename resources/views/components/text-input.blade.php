@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'border-white/10 bg-[#0a0a0f]/50 text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm'
]) !!}>