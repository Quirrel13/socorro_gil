<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold">Informações do perfil</h2>
        <p class="mt-1 text-sm text-ifb-dim">Atualize seu nome e o e-mail usado para entrar.</p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')

        <x-ifb.input name="name" label="Nome completo" :value="$user->name" required autofocus autocomplete="name" />
        <x-ifb.input name="email" type="email" label="E-mail (login)" :value="$user->email" required autocomplete="username" />

        <div class="flex items-center gap-4 pt-2">
            <x-ifb.button><x-ifb.icon name="check" :size="16" /> Salvar</x-ifb.button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-ifb-success">Salvo.</p>
            @endif
        </div>
    </form>
</section>