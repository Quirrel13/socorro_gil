<x-ifb.layout titulo="Nova Solicitação">
    <div class="max-w-md">
        <x-ifb.page-header title="Solicitar aumento de limite" :back="route('solicitacao.index')" />

        <x-ifb.card>
            @if ($contas->isEmpty())
                <p class="text-sm text-center py-4 text-ifb-dim">Você ainda não possui contas de clientes.</p>
            @else
                <form method="POST" action="{{ route('solicitacao.store') }}" class="space-y-4">
                    @csrf

                    <div class="flex flex-col gap-1.5">
                        <label for="conta_id" class="text-xs font-medium text-ifb-dim">Conta do cliente</label>
                        <select id="conta_id" name="conta_id" required
                            class="w-full px-3 py-2.5 rounded-xl text-sm bg-ifb-secondary border border-ifb-line text-ifb-text focus:border-ifb-primary focus:ring-2 focus:ring-ifb-primary/20">
                            <option value="">Selecione...</option>
                            @foreach ($contas as $conta)
                                <option value="{{ $conta->id }}" @selected((int) old('conta_id', request('conta_id')) === $conta->id)>
                                    {{ $conta->cliente?->name }} — Conta {{ $conta->id }} (limite atual R$ {{ number_format((float) $conta->limite, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                        @error('conta_id')
                            <p class="text-xs text-ifb-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-ifb.input name="limite" type="number" step="0.01" min="0.01" prefix="R$" label="Novo limite" placeholder="0,00" required />

                    <div class="h-px my-4 bg-white/5"></div>

                    <x-ifb.button size="lg" class="w-full justify-center">Enviar solicitação</x-ifb.button>
                </form>
            @endif
        </x-ifb.card>
    </div>
</x-ifb.layout>