@props(['name', 'tone' => 'primary', 'box' => 'w-10 h-10 rounded-xl text-sm'])

@php
    $iniciais = collect(explode(' ', trim($name)))
        ->filter()
        ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
        ->take(2)
        ->implode('');

    $tons = [
        'primary' => 'bg-ifb-primary-soft text-ifb-accent',
        'warning' => 'bg-ifb-warning-soft text-ifb-warning',
        'solid' => 'bg-ifb-primary text-white',
    ];
@endphp
<div {{ $attributes->merge(['class' => 'flex items-center justify-center font-semibold shrink-0 '.$box.' '.($tons[$tone] ?? $tons['primary'])]) }}>{{ $iniciais }}</div>