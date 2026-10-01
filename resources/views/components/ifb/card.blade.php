@props(['padded' => true])

<div {{ $attributes->merge(['class' => 'rounded-2xl bg-ifb-card border border-ifb-line '.($padded ? 'p-5' : '')]) }}>
    {{ $slot }}
</div>