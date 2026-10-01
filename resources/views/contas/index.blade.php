<x-ifb.layout titulo="Meus Clientes">
    @php($total = $contas->count())

    <x-ifb.page-header title="Meus Clientes"
        :subtitle="$total.' cliente'.($total !== 1 ? 's' : '').' associado'.($total !== 1 ? 's' : '')">
        @can('create', App\Models\Conta::class)
            <x-ifb.button :href="route('conta.create')"><x-ifb.icon name="plus" /> Criar Conta</x-ifb.button>
        @endcan
    </x-ifb.page-header>

    @if ($contas->isEmpty())
        <x-ifb.card>
            <p class="text-sm text-center py-6 text-ifb-dim">Nenhum cliente associado</p>
        </x-ifb.card>
    @else
        <div class="space-y-2">
            @foreach ($contas as $conta)
                <a href="{{ route('conta.show', $conta) }}"
                   class="w-full flex items-center justify-between p-4 rounded-2xl text-left transition-all bg-ifb-card border border-ifb-line hover:border-ifb-primary/40">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-ifb.avatar :name="$conta->cliente?->name ?? '?'" />
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                <span class="font-medium">{{ $conta->cliente?->name ?? 'Cliente removido' }}</span>
                                @if ($conta->bloqueado)
                                    <x-ifb.badge variant="danger">Bloqueada</x-ifb.badge>
                                @endif
                                @if ($conta->limite_pendente)
                                    <x-ifb.badge variant="warning">Limite pendente</x-ifb.badge>
                                @endif
                            </div>
                            <p class="text-xs font-mono text-ifb-dim">Conta {{ $conta->id }} · {{ $conta->cliente?->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-ifb.money :value="$conta->saldo" class="font-mono text-sm font-semibold hidden sm:block" />
                        <span class="text-ifb-dim"><x-ifb.icon name="chevron-right" :size="16" /></span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-ifb.layout>