@props(['title', 'subtitle' => null, 'back' => null])

@if ($back)
    <a href="{{ $back }}" class="inline-flex items-center gap-2 text-sm mb-6 text-ifb-dim hover:opacity-70 transition-opacity">
        <x-ifb.icon name="back" :size="20" /> Voltar
    </a>
@endif

<div class="flex items-center justify-between gap-4 mb-6 flex-wrap">
    <div>
        <h1 class="text-2xl font-bold">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-sm mt-1 text-ifb-dim">{{ $subtitle }}</p>
        @endif
    </div>
    <div class="flex items-center gap-2">
        {{ $slot }}
    </div>
</div>