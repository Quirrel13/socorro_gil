<x-ifb.layout titulo="Logs de Auditoria">
    @php
        $acoes = ['created' => 'Cadastro', 'updated' => 'Alteração', 'deleted' => 'Remoção', 'restored' => 'Restauração'];
        $variantes = ['created' => 'success', 'updated' => 'purple', 'deleted' => 'danger', 'restored' => 'warning'];
        $entidades = ['User' => 'Usuário', 'Conta' => 'Conta', 'Solicitacao' => 'Solicitação de limite'];
        $campos = [
            'name' => 'nome', 'email' => 'e-mail', 'saldo' => 'saldo', 'limite' => 'limite',
            'bloqueado' => 'bloqueada', 'status' => 'status', 'motivo_recusa' => 'motivo da recusa',
            'avaliador_id' => 'avaliador', 'solicitante_id' => 'solicitante', 'conta_id' => 'conta',
            'cliente_id' => 'cliente', 'gerente_id' => 'gerente', 'role_id' => 'perfil', 'deleted_at' => 'removido em',
        ];

        $lista = fn ($v) => is_string($v) ? (json_decode($v, true) ?? []) : (array) $v;

        $valorTexto = function ($campo, $valor) {
            if ($campo === 'bloqueado') {
                return $valor ? 'sim' : 'não';
            }
            if (is_null($valor)) {
                return '—';
            }
            if (is_bool($valor)) {
                return $valor ? 'sim' : 'não';
            }
            if (is_array($valor)) {
                return json_encode($valor, JSON_UNESCAPED_UNICODE);
            }
            return (string) $valor;
        };
    @endphp

    <x-ifb.page-header title="Logs de Auditoria" subtitle="Atividades realizadas pelos gerentes de conta" />

    <x-ifb.card :padded="false" class="overflow-hidden">
        @forelse ($logs as $log)
            @php
                $antigos = $lista($log->old_values);
                $novos = $lista($log->new_values);
                $chaves = array_values(array_unique(array_merge(array_keys($antigos), array_keys($novos))));
                $entidade = $entidades[class_basename($log->auditable_type)] ?? class_basename($log->auditable_type);
            @endphp

            <div class="px-5 py-4 border-b border-white/5 last:border-b-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 mt-0.5 bg-ifb-primary-soft text-ifb-accent">
                            <x-ifb.icon name="log" :size="14" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-0.5 flex-wrap">
                                <p class="text-sm font-semibold">{{ $entidade }} #{{ $log->auditable_id }}</p>
                                <x-ifb.badge :variant="$variantes[$log->event] ?? 'default'">{{ $acoes[$log->event] ?? $log->event }}</x-ifb.badge>
                            </div>
                            <p class="text-xs text-ifb-dim">por {{ $log->user?->name ?? 'Sistema' }}</p>

                            @if (count($chaves) > 0)
                                <ul class="mt-2 space-y-0.5">
                                    @foreach ($chaves as $campo)
                                        <li class="text-xs font-mono text-ifb-dim">
                                            <span class="text-ifb-soft">{{ $campos[$campo] ?? $campo }}</span>:
                                            @if (array_key_exists($campo, $antigos))
                                                {{ $valorTexto($campo, $antigos[$campo]) }} →
                                            @endif
                                            {{ $valorTexto($campo, $novos[$campo] ?? null) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                    <span class="font-mono text-[10px] shrink-0 mt-1 text-ifb-dim">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>
        @empty
            <p class="text-sm py-8 text-center text-ifb-dim">Nenhum registro encontrado</p>
        @endforelse
    </x-ifb.card>

    {{ $logs->links('vendor.pagination.ifbank') }}
</x-ifb.layout>