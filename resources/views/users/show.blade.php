<x-ifb.layout :titulo="$user->name">
    <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 text-sm mb-6 text-ifb-dim hover:opacity-70 transition-opacity">
        <x-ifb.icon name="back" :size="20" /> Voltar
    </a>

    <x-ifb.card class="mb-6 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <x-ifb.avatar :name="$user->name" tone="warning" box="w-12 h-12 rounded-2xl text-base" />
            <div>
                <h2 class="font-bold text-lg">{{ $user->name }}</h2>
                <p class="text-xs font-mono text-ifb-dim">{{ $user->email }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @can('update', $user)
                <x-ifb.button :href="route('users.edit', $user)" variant="outline" size="sm">
                    <x-ifb.icon name="edit" :size="16" /> Editar
                </x-ifb.button>
            @endcan

            @can('delete', $user)
                <x-ifb.confirmar-remocao :action="route('users.destroy', $user)" />
            @endcan
        </div>
    </x-ifb.card>

    <h3 class="font-semibold mb-3">Clientes associados ({{ $user->contaGerente->count() }})</h3>

    <div class="space-y-2">
        @forelse ($user->contaGerente as $conta)
            <div class="flex items-center justify-between p-4 rounded-xl bg-ifb-card border border-ifb-line">
                <div class="flex items-center gap-3">
                    <x-ifb.avatar :name="$conta->cliente?->name ?? '?'" box="w-8 h-8 rounded-lg text-xs" />
                    <div>
                        <div class="flex items-center gap-2">
                            <p class="text-sm font-medium">{{ $conta->cliente?->name ?? 'Cliente removido' }}</p>
                            @if ($conta->bloqueado)
                                <x-ifb.badge variant="danger">Bloqueada</x-ifb.badge>
                            @endif
                        </div>
                        <p class="text-xs font-mono text-ifb-dim">Conta {{ $conta->id }}</p>
                    </div>
                </div>
                <x-ifb.money :value="$conta->saldo" class="font-mono text-sm font-semibold" />
            </div>
        @empty
            <x-ifb.card>
                <p class="text-sm text-center py-4 text-ifb-dim">Nenhum cliente</p>
            </x-ifb.card>
        @endforelse
    </div>
</x-ifb.layout>