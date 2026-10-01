<x-ifb.layout titulo="Editar Gerente">
    <div class="max-w-md">
        <x-ifb.page-header title="Editar Gerente de Conta" :back="route('users.show', $user)" />

        <x-ifb.card>
            <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-ifb.input name="name" label="Nome completo" :value="$user->name" required />
                <x-ifb.input name="email" type="email" label="E-mail (login)" :value="$user->email" disabled />
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