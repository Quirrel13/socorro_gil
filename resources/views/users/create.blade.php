<x-ifb.layout titulo="Novo Gerente">
    <div class="max-w-md">
        <x-ifb.page-header title="Criar Gerente de Conta" :back="route('users.index')" />

        <x-ifb.card>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf

                <x-ifb.input name="name" label="Nome completo" placeholder="Maria Oliveira" required />
                <x-ifb.input name="email" type="email" label="E-mail (login)" placeholder="maria@email.com" required autocomplete="off" />
                <x-ifb.input name="password" type="password" label="Senha" required autocomplete="new-password" />
                <x-ifb.input name="password_confirmation" type="password" label="Confirmar senha" required autocomplete="new-password" />

                <div class="h-px my-4 bg-white/5"></div>

                <x-ifb.button size="lg" class="w-full justify-center">
                    <x-ifb.icon name="plus" /> Criar Gerente de Conta
                </x-ifb.button>
            </form>
        </x-ifb.card>
    </div>
</x-ifb.layout>