<x-ifb.layout :titulo="'Editar conta '.$conta->id">
    <div class="max-w-md">
        <x-ifb.page-header title="Editar cliente" :subtitle="'Conta '.$conta->id" :back="route('conta.show', $conta)" />

        <x-ifb.card class="mb-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-ifb-dim">Saldo</span>
                <x-ifb.money :value="$conta->saldo" class="font-mono font-semibold" />
            </div>
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-ifb-dim">Limite</span>
                <x-ifb.money :value="$conta->limite" class="font-mono font-semibold" />
            </div>
            <p class="text-xs text-ifb-dim">O limite só pode ser alterado por solicitação aprovada pelo gerente geral.</p>
        </x-ifb.card>

        <x-ifb.card>
            <form method="POST" action="{{ route('conta.update', $conta) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-ifb.input name="name" label="Nome completo" :value="$conta->cliente?->name" required />
                <x-ifb.input name="email" type="email" label="E-mail (login)" :value="$conta->cliente?->email" required autocomplete="off" />
                <x-ifb.input name="password" type="password" label="Nova senha" hint="Deixe em branco para manter a senha atual." autocomplete="new-password" />
                <x-ifb.input name="password_confirmation" type="password" label="Confirmar nova senha" autocomplete="new-password" />

                <div class="h-px my-4 bg-white/5"></div>

                <x-ifb.button size="lg" class="w-full justify-center">
                    <x-ifb.icon name="check" /> Salvar alterações
                </x-ifb.button>
            </form>
        </x-ifb.card>
    </div>
</x-ifb.layout>