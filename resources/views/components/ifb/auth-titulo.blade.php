@props(['titulo', 'subtitulo' => null])

<div class="text-center mb-8">
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-gradient-to-br from-ifb-primary to-ifb-accent">
        <span class="font-mono text-xl font-bold text-white">IF</span>
    </div>
    <h1 class="text-2xl font-bold tracking-tight mb-1">{{ $titulo }}</h1>
    @if ($subtitulo)
        <p class="text-sm text-ifb-dim">{{ $subtitulo }}</p>
    @endif
</div>