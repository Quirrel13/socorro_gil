<x-ifb.guest titulo="Redefinir senha">
    <x-ifb.auth-titulo titulo="Redefinir senha" subtitulo="Escolha uma nova senha para a sua conta" />

    <x-ifb.card>
        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-ifb.input name="email" type="email" label="E-mail" :value="$request->email" required autofocus autocomplete="username" />
            <x-ifb.input name="password" type="password" label="Nova senha" required autocomplete="new-password" />
            <x-ifb.input name="password_confirmation" type="password" label="Confirmar nova senha" required autocomplete="new-password" />

            <x-ifb.button size="lg" class="w-full justify-center">Redefinir senha</x-ifb.button>
        </form>
    </x-ifb.card>
</x-ifb.guest>