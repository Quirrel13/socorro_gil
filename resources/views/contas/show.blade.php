<x-ifb.layout :titulo="'Conta '.$conta->id">
    @php
        $pendente = $conta->solicitacoes->first(fn ($s) => $s->status === \App\Enums\StatusSolicitacao::PENDENTE);
        $historico = $conta->solicitacoes
            ->reject(fn ($s) => $s->status === \App\Enums\StatusSolicitacao::PENDENTE)
            ->sortByDesc('id');
        $totalInvestido = $conta->investimentos->sum(fn ($i) => (float) $i->valor);
        $abaInicial = ($errors->has('limite') || $errors->has('conta_id')) ? 'limite' : 'dados';
    @endphp

    <a href="{{ route('conta.index') }}" class="inline-flex items-center gap-2 text-sm mb-6 text-ifb-dim hover:opacity-70 transition-opacity">
        <x-ifb.icon name="back" :size="20" /> Voltar
    </a>

    {{-- Cabeçalho do cliente --}}
    <x-ifb.card class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <x-ifb.avatar :name="$conta->cliente?->name ?? '?'" box="w-12 h-12 rounded-2xl text-base" />
            <div>
                <div class="flex items-center gap-2 mb-0.5">
                    <h2 class="font-bold text-lg">{{ $conta->cliente?->name ?? 'Cliente removido' }}</h2>
                    @if ($conta->bloqueado)
                        <x-ifb.badge variant="danger">Bloqueada</x-ifb.badge>
                    @endif
                </div>
                <p class="text-xs font-mono text-ifb-dim">Conta {{ $conta->id }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('bloquear', $conta)
                @if (!$conta->bloqueado)
                    <form method="POST" action="{{ route('conta.bloquear', $conta) }}">
                        @csrf
                        @method('PATCH')
                        <x-ifb.button variant="danger" size="sm"><x-ifb.icon name="lock" :size="16" /> Bloquear</x-ifb.button>
                    </form>
                @endif
            @endcan

            @can('desbloquear', $conta)
                @if ($conta->bloqueado)
                    <form method="POST" action="{{ route('conta.desbloquear', $conta) }}">
                        @csrf
                        @method('PATCH')
                        <x-ifb.button variant="success" size="sm"><x-ifb.icon name="unlock" :size="16" /> Desbloquear</x-ifb.button>
                    </form>
                @endif
            @endcan

            @can('extrato', $conta)
                <x-ifb.button :href="route('conta.extrato', $conta)" variant="outline" size="sm">
                    <x-ifb.icon name="list" :size="16" /> Extrato
                </x-ifb.button>
            @endcan

            @can('update', $conta)
                <x-ifb.button :href="route('conta.edit', $conta)" variant="outline" size="sm">
                    <x-ifb.icon name="edit" :size="16" /> Editar
                </x-ifb.button>
            @endcan

            @can('delete', $conta)
                <x-ifb.confirmar-remocao :action="route('conta.destroy', $conta)" />
            @endcan
        </div>
    </x-ifb.card>

    {{-- Abas --}}
    <div x-data="{ aba: '{{ $abaInicial }}' }">
        <div class="flex gap-1 mb-6 p-1 rounded-xl bg-ifb-muted">
            @foreach (['dados' => 'Dados', 'investimentos' => 'Investimentos', 'limite' => 'Limite'] as $chave => $rotulo)
                <button type="button"
                    x-on:click="aba = '{{ $chave }}'"
                    x-bind:class="aba === '{{ $chave }}' ? 'bg-ifb-card text-ifb-text border-ifb-line' : 'bg-transparent text-ifb-dim border-transparent'"
                    class="flex-1 py-2 rounded-lg text-sm font-medium transition-all border">
                    {{ $rotulo }}
                </button>
            @endforeach
        </div>

        {{-- Dados --}}
        <div x-show="aba === 'dados'">
            <x-ifb.card>
                <h3 class="font-semibold mb-4">Dados do cliente</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">Nome</p>
                        <p class="text-sm font-medium font-mono">{{ $conta->cliente?->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">E-mail</p>
                        <p class="text-sm font-medium font-mono break-all">{{ $conta->cliente?->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">Conta</p>
                        <p class="text-sm font-medium font-mono">{{ $conta->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">Situação</p>
                        <p class="text-sm font-medium font-mono">{{ $conta->bloqueado ? 'Bloqueada' : 'Ativa' }}</p>
                    </div>
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">Saldo</p>
                        <x-ifb.money :value="$conta->saldo" class="text-sm font-medium font-mono" />
                    </div>
                    <div>
                        <p class="text-xs mb-0.5 text-ifb-dim">Limite</p>
                        <x-ifb.money :value="$conta->limite" class="text-sm font-medium font-mono" />
                    </div>
                </div>
            </x-ifb.card>
        </div>

        {{-- Investimentos --}}
        <div x-show="aba === 'investimentos'" x-cloak class="space-y-3">
            <div class="rounded-2xl p-6 bg-gradient-to-br from-[#0D0820] to-ifb-secondary border border-ifb-line-strong">
                <p class="text-xs font-mono mb-1 text-ifb-accent">TOTAL INVESTIDO</p>
                <x-ifb.money :value="$totalInvestido" class="text-3xl font-bold" />
            </div>

            @forelse ($conta->investimentos as $investimento)
                <x-ifb.card>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-semibold mb-0.5">{{ $investimento->tipo?->nome }}</p>
                            <x-ifb.badge variant="purple">Aplicação</x-ifb.badge>
                        </div>
                        <x-ifb.money :value="$investimento->valor" class="font-mono font-semibold" />
                    </div>
                </x-ifb.card>
            @empty
                <x-ifb.card>
                    <p class="text-sm text-center py-4 text-ifb-dim">Nenhum investimento</p>
                </x-ifb.card>
            @endforelse
        </div>

        {{-- Limite --}}
        <div x-show="aba === 'limite'" x-cloak class="space-y-4">
            <x-ifb.card>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-ifb-dim">Limite atual</span>
                    <x-ifb.money :value="$conta->limite" class="font-mono font-bold text-lg" />
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-ifb-dim">Saldo</span>
                    <x-ifb.money :value="$conta->saldo" class="font-mono font-semibold" />
                </div>
            </x-ifb.card>

            @if ($pendente)
                <div class="rounded-xl p-4 flex items-start gap-3 bg-ifb-warning-soft border border-ifb-warning-line">
                    <span class="text-ifb-warning"><x-ifb.icon name="alert" /></span>
                    <div>
                        <p class="text-sm font-semibold text-ifb-warning">Solicitação pendente</p>
                        <p class="text-xs mt-0.5 text-ifb-dim">
                            Limite solicitado: <x-ifb.money :value="$pendente->limite" /> · {{ $pendente->created_at->format('d/m/Y') }}
                        </p>
                        <p class="text-xs text-ifb-dim">Aguardando aprovação do gerente geral</p>
                    </div>
                </div>
            @else
                @can('solicitarLimite', $conta)
                    <x-ifb.card>
                        <h4 class="font-semibold mb-3">Solicitar aumento de limite</h4>
                        <form method="POST" action="{{ route('solicitacao.store') }}" class="flex gap-3 items-start">
                            @csrf
                            <input type="hidden" name="conta_id" value="{{ $conta->id }}">
                            <div class="flex-1">
                                <x-ifb.input name="limite" type="number" step="0.01" min="0.01" prefix="R$" placeholder="0,00" required />
                            </div>
                            <x-ifb.button>Solicitar</x-ifb.button>
                        </form>
                    </x-ifb.card>
                @endcan
            @endif

            @if ($historico->isNotEmpty())
                <div>
                    <p class="text-sm font-medium mb-2">Histórico de solicitações</p>
                    @foreach ($historico as $s)
                        <div class="flex items-center justify-between py-2.5 border-b border-white/5 last:border-b-0">
                            <div>
                                <p class="text-sm">Limite solicitado: <x-ifb.money :value="$s->limite" /></p>
                                <p class="text-xs text-ifb-dim">
                                    {{ $s->created_at->format('d/m/Y') }}
                                    @if ($s->motivo_recusa) · Motivo: {{ $s->motivo_recusa }} @endif
                                </p>
                            </div>
                            <x-ifb.badge :variant="$s->status === \App\Enums\StatusSolicitacao::APROVADA ? 'success' : 'danger'">
                                {{ $s->status === \App\Enums\StatusSolicitacao::APROVADA ? 'Aprovado' : 'Recusado' }}
                            </x-ifb.badge>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-ifb.layout>