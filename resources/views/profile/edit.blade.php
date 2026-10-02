<x-ifb.layout titulo="Meu perfil">
    <x-ifb.page-header title="Meu perfil" subtitle="Gerencie seus dados de acesso" />

    <div class="max-w-xl space-y-6">
        <x-ifb.card>
            @include('profile.partials.update-profile-information-form')
        </x-ifb.card>

        <x-ifb.card>
            @include('profile.partials.update-password-form')
        </x-ifb.card>
    </div>
</x-ifb.layout>