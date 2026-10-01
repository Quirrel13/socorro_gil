@props(['action', 'label' => 'Remover', 'mensagem' => 'Confirmar?'])

<div x-data="{ confirmando: false }" class="flex items-center gap-2">
    <div x-show="!confirmando">
        <x-ifb.button type="button" variant="danger" size="sm" x-on:click="confirmando = true">
            <x-ifb.icon name="trash" :size="16" /> {{ $label }}
        </x-ifb.button>
    </div>

    <form x-show="confirmando" x-cloak method="POST" action="{{ $action }}" class="flex items-center gap-2">
        @csrf
        @method('DELETE')
        <span class="text-xs text-ifb-dim">{{ $mensagem }}</span>
        <x-ifb.button variant="danger" size="sm"><x-ifb.icon name="check" :size="16" /> Sim</x-ifb.button>
        <x-ifb.button type="button" variant="ghost" size="sm" x-on:click="confirmando = false">
            <x-ifb.icon name="x" :size="16" /> Não
        </x-ifb.button>
    </form>
</div>