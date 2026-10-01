<x-ifb.layout titulo="Logs de Auditoria">
    <x-ifb.page-header title="Logs de Auditoria" subtitle="Atividades dos gerentes de conta e do gerente geral" />

    <x-ifb.card :padded="false" class="overflow-hidden">
        @forelse ($logs as $log)
            <div class="px-5 py-4 border-b border-white/5 last:border-b-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 bg-ifb-primary-soft text-ifb-accent">
                            <x-ifb.icon name="log" :size="14" />
                        </div>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                <p class="text-sm font-semibold">{{ $log['titulo'] }}</p>
                                <x-ifb.badge :variant="$log['variante']">{{ $log['entidade'] }}</x-ifb.badge>
                            </div>

                            <p class="text-xs text-ifb-dim">
                                por <span class="text-ifb-soft">{{ $log['autor'] }}</span>
                                @if ($log['contexto']) · {{ $log['contexto'] }} @endif
                            </p>

                            @if (count($log['mudancas']) > 0)
                                <ul class="mt-2 space-y-0.5">
                                    @foreach ($log['mudancas'] as $mudanca)
                                        <li class="text-xs font-mono text-ifb-dim">
                                            <span class="text-ifb-soft">{{ $mudanca['campo'] }}</span>:
                                            @if ($mudanca['antes'] !== null)
                                                {{ $mudanca['antes'] }} →
                                            @endif
                                            <span class="text-ifb-text">{{ $mudanca['depois'] }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <span class="font-mono text-[10px] shrink-0 mt-1 text-ifb-dim">{{ $log['data']->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @empty
            <p class="text-sm py-8 text-center text-ifb-dim">Nenhum registro encontrado</p>
        @endforelse
    </x-ifb.card>

    {{ $logs->links('vendor.pagination.ifbank') }}
</x-ifb.layout>