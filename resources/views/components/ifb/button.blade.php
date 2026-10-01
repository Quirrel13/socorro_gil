@props(['variant' => 'primary', 'size' => 'md', 'href' => null, 'type' => 'submit'])

@php
    $variantes = [
        'primary' => 'bg-ifb-primary hover:bg-ifb-primary-hover text-white',
        'ghost' => 'bg-transparent hover:bg-white/5 text-ifb-soft',
        'danger' => 'bg-ifb-danger-soft hover:bg-ifb-danger-hover text-ifb-danger border border-ifb-danger-line',
        'outline' => 'bg-transparent border border-ifb-line-strong hover:border-ifb-primary text-ifb-soft hover:text-white',
        'success' => 'bg-ifb-success-soft hover:bg-ifb-success-hover text-ifb-success border border-ifb-success-line',
    ];
    $tamanhos = ['sm' => 'px-3 py-1.5 text-xs', 'md' => 'px-4 py-2 text-sm', 'lg' => 'px-6 py-3 text-base'];
    $classes = 'inline-flex items-center gap-2 font-medium rounded-lg transition-all duration-200 cursor-pointer disabled:opacity-40 disabled:cursor-not-allowed '
        .($variantes[$variant] ?? $variantes['primary']).' '.($tamanhos[$size] ?? $tamanhos['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif