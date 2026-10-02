<x-ifb.guest titulo="Recuperar senha">
    <x-ifb.auth-titulo titulo="Recuperar senha" subtitulo="Informe seu e-mail e enviaremos um link para redefinir a senha" />

    @if (session('status'))
        <div class="mb-4 p-3 rounded-xl text-sm bg-ifb-success-soft border border-ifb-success-line text-ifb-success">
            Enviamos o link de recuperação para o seu e-mail.
        </div>
    @endif

    <x-ifb.card>
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <x-ifb.input name="email" type="email" label="E-mail" placeholder="seu@email.com" required autofocus />

            <x-ifb.button size="lg" class="w-full justify-center">Enviar link de recuperação</x-ifb.button>
        </form>

        <a href="{{ route('login') }}" class="block text-center text-xs mt-4 text-ifb-accent hover:underline">Voltar ao login</a>
    </x-ifb.card>
</x-ifb.guest>