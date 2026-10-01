<x-ifb.layout :titulo="'Extrato · Conta '.$conta->id">
    @php
        $entradas = $movimentacoes->where('sentido', 'entrada')->sum(fn ($l) => (float) $l['valor']);
        $saidas = $movimentacoes->where('sentido', 'saida')->sum(fn ($l) => (float) $l['valor']);
    @endphp

    <x-ifb.page-header title="Extrato"
        :subtitle="($conta->cliente?->name ?? 'Cliente').' · Conta '.$conta->id"
        :back="route('conta.show', $conta)" />

    {{-- Filtro de período --}}
    <x-ifb.card class="mb-6">
        <form method="GET" action="{{ route('conta.extrato', $conta) }}" class="flex items-end gap-3 flex-wrap">
            <div class="flex-1 min-w-[140px]">
                <x-ifb.input name="data_inicial" type="date" label="Data inicial" :value="request('data_inicial')" />
            </div>
            <div class="flex-1 min-w-[140px]">
                <x-ifb.input name="data_final" type="date" label="Data final" :value="request('data_final')" />
            </div>
            <div class="flex gap-2">
                <x-ifb.button>Filtrar</x-ifb.button>
                <x-ifb.button :href="route('conta.extrato', $conta)" variant="ghost">Limpar</x-ifb.button>
            </div>
        </form>
    </x-ifb.card>

    {{-- Totais --}}
    <div class="grid grid-cols-2 gap-3 mb-6">
        <x-ifb.card>
            <p class="text-xs font-mono mb-1 text-ifb-success">ENTRADAS</p>
            <x-ifb.money :value="$entradas" class="text-xl font-bold font-mono" />
        </x-ifb.card>
        <x-ifb.card>
            <p class="text-xs font-mono mb-1 text-ifb-danger">SAÍDAS</p>
            <x-ifb.money :value="$saidas" class="text-xl font-bold font-mono" />
        </x-ifb.card>
    </div>

    {{-- Movimentações --}}
    <x-ifb.card :padded="false" class="overflow-hidden">
        <div class="px-5 py-4 border-b border-ifb-line">
            <p class="text-sm font-medium">Movimentações</p>
        </div>
        <div class="px-5">
            @forelse ($movimentacoes as $linha)
                <x-ifb.extrato-linha :linha="$linha" />
            @empty
                <p class="text-sm py-8 text-center text-ifb-dim">Nenhuma movimentação encontrada</p>
            @endforelse
        </div>
    </x-ifb.card>
</x-ifb.layout>