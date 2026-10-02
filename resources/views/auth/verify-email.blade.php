<x-ifb.guest titulo="Verificar e-mail">
    <x-ifb.auth-titulo titulo="Verifique seu e-mail" subtitulo="Enviamos um link de confirmação para o seu e-mail. Se não recebeu, podemos enviar outro." />

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 p-3 rounded-xl text-sm bg-ifb-success-soft border border-ifb-success-line text-ifb-success">
            Um novo link de verificação foi enviado para o seu e-mail.
        </div>
    @endif

    <x-ifb.card>
        <div class="flex items-center justify-between gap-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <x-ifb.button>Reenviar e-mail</x-ifb.button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-ifb.button variant="ghost">Sair</x-ifb.button>
            </form>
        </div>
    </x-ifb.card>
</x-ifb.guest>