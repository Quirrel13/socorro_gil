<x-ifb.layout titulo="Início">
    <x-ifb.page-header :title="'Olá, '.explode(' ', auth()->user()->name)[0]" subtitle="Bem-vindo ao IFBANK" />

    <x-ifb.card>
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-ifb-primary-soft text-ifb-accent shrink-0">
                <x-ifb.icon name="user" :size="22" />
            </div>
            <div>
                <p class="font-semibold mb-1">Acesso do cliente</p>
                <p class="text-sm text-ifb-dim">
                    Saldo, Pix, extrato e investimentos estão disponíveis no aplicativo do cliente.
                    Esta área é destinada aos gerentes.
                </p>
            </div>
        </div>
    </x-ifb.card>
</x-ifb.layout><x-ifb.layout titulo="Início">
    <x-ifb.page-header :title="'Olá, '.explode(' ', auth()->user()->name)[0]" subtitle="Bem-vindo ao IFBANK" />

    <x-ifb.card>
        <div class="flex items-start gap-4">
            <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-ifb-primary-soft text-ifb-accent shrink-0">
                <x-ifb.icon name="user" :size="22" />
            </div>
            <div>
                <p class="font-semibold mb-1">Acesso do cliente</p>
                <p class="text-sm text-ifb-dim">
                    Saldo, Pix, extrato e investimentos estão disponíveis no aplicativo do cliente.
                    Esta área é destinada aos gerentes.
                </p>
            </div>
        </div>
    </x-ifb.card>
</x-ifb.layout>