<x-ifb.guest titulo="Início">
    <x-ifb.auth-titulo titulo="IFBANK" subtitulo="Sistema bancário · IFPR Paranaguá" />

    <x-ifb.card>
        @auth
            <x-ifb.button :href="route('dashboard')" size="lg" class="w-full justify-center">Ir para o painel</x-ifb.button>
        @else
            <x-ifb.button :href="route('login')" size="lg" class="w-full justify-center">Entrar</x-ifb.button>
        @endauth
    </x-ifb.card>
</x-ifb.guest>