<section>
    <header class="mb-6">
        <h2 class="text-lg font-semibold">Alterar senha</h2>
        <p class="mt-1 text-sm text-ifb-dim">Use uma senha longa e difícil de adivinhar.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('PUT')

        <x-ifb.input name="current_password" type="password" label="Senha atual" bag="updatePassword" autocomplete="current-password" />
        <x-ifb.input name="password" type="password" label="Nova senha" bag="updatePassword" autocomplete="new-password" />
        <x-ifb.input name="password_confirmation" type="password" label="Confirmar nova senha" bag="updatePassword" autocomplete="new-password" />

        <div class="flex items-center gap-4 pt-2">
            <x-ifb.button><x-ifb.icon name="check" :size="16" /> Alterar senha</x-ifb.button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                   class="text-sm text-ifb-success">Senha alterada.</p>
            @endif
        </div>
    </form>
</section>