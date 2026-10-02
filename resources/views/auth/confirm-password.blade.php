<x-ifb.guest titulo="Confirmar senha">
    <x-ifb.auth-titulo titulo="Confirmar senha" subtitulo="Esta é uma área segura. Confirme sua senha para continuar" />

    <x-ifb.card>
        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <x-ifb.input name="password" type="password" label="Senha" required autofocus autocomplete="current-password" />

            <x-ifb.button size="lg" class="w-full justify-center">Confirmar</x-ifb.button>
        </form>
    </x-ifb.card>
</x-ifb.guest>