<x-ifb.layout titulo="Solicitações de Limite">
    @php
        $listaPendentes = $solicitacoes->filter(fn ($s) => $s->status === \App\Enums\StatusSolicitacao::PENDENTE);
        $resolvidas = $solicitacoes->reject(fn ($s) => $s->status === \App\Enums\StatusSolicitacao::PENDENTE);
        $qtd = $listaPendentes->count();
    @endphp

    <x-ifb.page-header title="Solicitações de Limite"
        :subtitle="$qtd.' pendente'.($qtd !== 1 ? 's' : '')">
        @can('create', App\Models\Solicitacao::class)
            <x-ifb.button :href="route('solicitacao.create')"><x-ifb.icon name="plus" /> Nova solicitação</x-ifb.button>
        @endcan
    </x-ifb.page-header>

    @if ($solicitacoes->isEmpty())
        <x-ifb.card>
            <p class="text-sm text-center py-6 text-ifb-dim">Nenhuma solicitação</p>
        </x-ifb.card>
    @endif

    {{-- Pendentes --}}
    @if ($listaPendentes->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-sm font-semibold mb-3 uppercase text-ifb-dim">Pendentes</h2>
            <div class="space-y-3">
                @foreach ($listaPendentes as $s)
                    <x-ifb.card>
                        <div x-data="{ recusando: {{ $errors->has('motivo_recusa') && (int) old('alvo') === $s->id ? 'true' : 'false' }} }">
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="font-semibold">{{ $s->conta?->cliente?->name }}</p>
                                        <x-ifb.badge variant="warning">Pendente</x-ifb.badge>
                                    </div>
                                    <p class="text-xs font-mono text-ifb-dim">Conta {{ $s->conta_id }} · Gerente: {{ $s->solicitante?->name }}</p>
                                    <p class="text-xs text-ifb-dim">Solicitado em {{ $s->created_at->format('d/m/Y') }}</p>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <x-ifb.money :value="$s->conta?->limite" class="font-mono text-sm line-through text-ifb-dim" />
                                        <span class="text-xs text-ifb-dim">→</span>
                                        <x-ifb.money :value="$s->limite" class="font-mono text-sm font-bold text-ifb-accent" />
                                    </div>
                                    <p class="text-xs text-ifb-dim">
                                        +<x-ifb.money :value="(float) $s->limite - (float) $s->conta?->limite" />
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-2 flex-wrap">
                                @can('aprovar', $s)
                                    <form method="POST" action="{{ route('solicitacao.aprovar', $s) }}">
                                        @csrf
                                        @method('PATCH')
                                        <x-ifb.button variant="success" size="sm"><x-ifb.icon name="check" :size="16" /> Aprovar</x-ifb.button>
                                    </form>
                                @endcan

                                @can('recusar', $s)
                                    <x-ifb.button type="button" variant="danger" size="sm" x-show="!recusando" x-on:click="recusando = true">
                                        <x-ifb.icon name="x" :size="16" /> Recusar
                                    </x-ifb.button>
                                @endcan

                                @cannot('aprovar', $s)
                                    <p class="text-xs text-ifb-warning">Aguardando aprovação do gerente geral</p>
                                @endcannot
                            </div>

                            @can('recusar', $s)
                                <form x-show="recusando" x-cloak method="POST" action="{{ route('solicitacao.recusar', $s) }}" class="mt-3 flex gap-2 items-start">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="alvo" value="{{ $s->id }}">
                                    <div class="flex-1">
                                        <input type="text" name="motivo_recusa" maxlength="255" required placeholder="Motivo da recusa"
                                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-ifb-secondary border border-ifb-line text-ifb-text placeholder:text-ifb-dim focus:border-ifb-primary focus:ring-2 focus:ring-ifb-primary/20">
                                        @if ((int) old('alvo') === $s->id)
                                            @error('motivo_recusa')
                                                <p class="text-xs mt-1 text-ifb-danger">{{ $message }}</p>
                                            @enderror
                                        @endif
                                    </div>
                                    <x-ifb.button variant="danger">Confirmar</x-ifb.button>
                                    <x-ifb.button type="button" variant="ghost" x-on:click="recusando = false">Cancelar</x-ifb.button>
                                </form>
                            @endcan
                        </div>
                    </x-ifb.card>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Histórico --}}
    @if ($resolvidas->isNotEmpty())
        <div>
            <h2 class="text-sm font-semibold mb-3 uppercase text-ifb-dim">Histórico</h2>
            <div class="space-y-2">
                @foreach ($resolvidas as $s)
                    @php($aprovada = $s->status === \App\Enums\StatusSolicitacao::APROVADA)
                    <div class="flex items-center justify-between gap-3 p-4 rounded-xl bg-ifb-card border border-ifb-line">
                        <div>
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="text-sm font-medium">{{ $s->conta?->cliente?->name }}</p>
                                <x-ifb.badge :variant="$aprovada ? 'success' : 'danger'">{{ $aprovada ? 'Aprovado' : 'Recusado' }}</x-ifb.badge>
                            </div>
                            <p class="text-xs font-mono text-ifb-dim">
                                Limite solicitado: <x-ifb.money :value="$s->limite" />
                                · {{ $s->updated_at->format('d/m/Y') }}
                                @if ($s->avaliador) · por {{ $s->avaliador->name }} @endif
                            </p>
                            @if (!$aprovada && $s->motivo_recusa)
                                <p class="text-xs mt-0.5 text-ifb-dim">Motivo: {{ $s->motivo_recusa }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</x-ifb.layout>