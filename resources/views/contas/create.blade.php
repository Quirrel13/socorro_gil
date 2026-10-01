<x-ifb.layout titulo="Criar Conta">
    <div class="max-w-md">
        <x-ifb.page-header title="Criar conta de cliente" :back="route('conta.index')" />

        <x-ifb.card>
            <form method="POST" action="{{ route('conta.store') }}" class="space-y-4">
                @csrf

                <x-ifb.input name="name" label="Nome completo" placeholder="Maria Oliveira" required />
                <x-ifb.input name="email" type="email" label="E-mail (login)" placeholder="maria@email.com" required autocomplete="off" />
                <x-ifb.input name="password" type="password" label="Senha" required autocomplete="new-password" />
                <x-ifb.input name="password_confirmation" type="password" label="Confirmar senha" required autocomplete="new-password" />

                <div class="h-px my-4 bg-white/5"></div>

                <div class="grid grid-cols-2 gap-3">
                    <x-ifb.input name="saldo" type="number" step="0.01" min="0" prefix="R$" label="Saldo inicial" placeholder="0,00" required />
                    <x-ifb.input name="limite" type="number" step="0.01" min="0" prefix="R$" label="Limite inicial" placeholder="0,00" required />
                </div>

                <div class="h-px my-4 bg-white/5"></div>

                <x-ifb.button size="lg" class="w-full justify-center">
                    <x-ifb.icon name="plus" /> Criar conta
                </x-ifb.button>
            </form>
        </x-ifb.card>
    </div>
</x-ifb.layout>