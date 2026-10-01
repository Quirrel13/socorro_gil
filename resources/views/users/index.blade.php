<x-ifb.layout titulo="Gerentes de Conta">
    @php($total = $usuarios->count())

    <x-ifb.page-header title="Gerentes de Conta"
        :subtitle="$total.' gerente'.($total !== 1 ? 's' : '').' cadastrado'.($total !== 1 ? 's' : '')">
        @can('create', App\Models\User::class)
            <x-ifb.button :href="route('users.create')"><x-ifb.icon name="plus" /> Novo Gerente</x-ifb.button>
        @endcan
    </x-ifb.page-header>

    @if ($usuarios->isEmpty())
        <x-ifb.card>
            <p class="text-sm text-center py-6 text-ifb-dim">Nenhum gerente cadastrado</p>
        </x-ifb.card>
    @else
        <div class="space-y-2">
            @foreach ($usuarios as $gerente)
                <a href="{{ route('users.show', $gerente) }}"
                   class="w-full flex items-center justify-between p-4 rounded-2xl transition-all bg-ifb-card border border-ifb-line hover:border-ifb-primary/40">
                    <div class="flex items-center gap-3 min-w-0">
                        <x-ifb.avatar :name="$gerente->name" tone="warning" />
                        <div class="min-w-0">
                            <p class="font-medium mb-0.5">{{ $gerente->name }}</p>
                            <p class="text-xs font-mono text-ifb-dim">{{ $gerente->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-ifb.badge variant="warning">
                            {{ $gerente->conta_gerente_count }} cliente{{ $gerente->conta_gerente_count !== 1 ? 's' : '' }}
                        </x-ifb.badge>
                        <span class="text-ifb-dim"><x-ifb.icon name="chevron-right" :size="16" /></span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</x-ifb.layout>