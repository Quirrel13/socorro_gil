@props(['variant' => 'default'])

@php
    $cores = [
        'default' => 'bg-white/5 text-ifb-soft',
        'success' => 'bg-ifb-success-soft text-ifb-success',
        'danger' => 'bg-ifb-danger-soft text-ifb-danger',
        'warning' => 'bg-ifb-warning-soft text-ifb-warning',
        'purple' => 'bg-ifb-primary-soft text-ifb-accent',
    ];
@endphp
<span {{ $attributes->merge(['class' => 'font-mono text-[10px] tracking-wider uppercase px-2 py-0.5 rounded-full '.($cores[$variant] ?? $cores['default'])]) }}>{{ $slot }}</span>