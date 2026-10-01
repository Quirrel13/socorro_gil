@props(['linha'])

@php
    $positivo = $linha['sentido'] === 'entrada';
    $ehPix = str_starts_with($linha['tipo'], 'pix');
    $rotulos = ['pix_enviado' => 'Pix Enviado', 'pix_recebido' => 'Pix Recebido', 'aplicacao' => 'Aplicação', 'resgate' => 'Resgate'];
    $variantes = ['pix_enviado' => 'danger', 'pix_recebido' => 'success', 'aplicacao' => 'purple', 'resgate' => 'warning'];
@endphp
<div class="flex items-center justify-between py-3.5 border-b border-white/5 last:border-b-0">
    <div class="flex items-center gap-3">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center {{ $positivo ? 'bg-ifb-success-soft text-ifb-success' : 'bg-ifb-primary-soft text-ifb-accent' }}">
            <x-ifb.icon :name="$ehPix ? 'pix' : 'chart'" :size="16" />
        </div>
        <div>
            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                <span class="text-sm font-medium">{{ $linha['descricao'] }}</span>
                <x-ifb.badge :variant="$variantes[$linha['tipo']] ?? 'default'">{{ $rotulos[$linha['tipo']] ?? $linha['tipo'] }}</x-ifb.badge>
            </div>
            <p class="text-xs font-mono text-ifb-dim">{{ $linha['data']->format('d/m/Y H:i') }}</p>
        </div>
    </div>
    <x-ifb.money :value="$linha['valor']" :signed="true" :color="true" class="font-mono text-sm font-semibold" />
</div>