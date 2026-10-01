<?php

namespace App\Services;

use App\Repositories\AuditRepository;
use App\Repositories\RoleRepository;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Models\Audit;

class AuditService extends BaseService
{
    // campos técnicos que não interessam a quem lê o log
    protected const OCULTOS = [
        'id', 'conta_id', 'cliente_id', 'gerente_id', 'solicitante_id',
        'avaliador_id', 'role_id', 'created_at', 'updated_at', 'deleted_at',
        'email_verified_at',
    ];

    protected const ROTULOS = [
        'name' => 'Nome',
        'email' => 'E-mail',
        'saldo' => 'Saldo',
        'limite' => 'Limite',
        'bloqueado' => 'Situação',
        'status' => 'Status',
        'motivo_recusa' => 'Motivo da recusa',
    ];

    public function __construct(
        protected AuditRepository $repository,
        protected RoleRepository $roleRepository
    ) {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }

    public function listarAtividadesDeGerentes(int $limite = 15)
    {
        $perfis = $this->roleRepository->list()->pluck('name', 'id')->all();

        return $this->repository
            ->listarAtividadesDeGerentes($limite)
            ->through(fn (Audit $audit) => $this->descrever($audit, $perfis));
    }

    protected function descrever(Audit $audit, array $perfis): array
    {
        $antigos = $this->paraArray($audit->old_values);
        $novos = $this->paraArray($audit->new_values);
        $alvo = $audit->auditable;

        $info = match (class_basename($audit->auditable_type)) {
            'Solicitacao' => $this->descreverSolicitacao($audit, $novos, $alvo),
            'Conta' => $this->descreverConta($audit, $antigos, $novos, $alvo),
            'User' => $this->descreverUsuario($audit, $antigos, $novos, $alvo, $perfis),
            default => [
                'entidade' => class_basename($audit->auditable_type),
                'titulo' => ucfirst($audit->event),
                'contexto' => '#'.$audit->auditable_id,
                'variante' => 'default',
                'extras' => [],
            ],
        };

        return [
            'data' => $audit->created_at,
            'autor' => $audit->user?->name ?? 'Sistema',
            'entidade' => $info['entidade'],
            'titulo' => $info['titulo'],
            'contexto' => $info['contexto'],
            'variante' => $info['variante'],
            'mudancas' => array_merge(
                $this->mudancas($audit->event, $antigos, $novos),
                $info['extras']
            ),
        ];
    }

    protected function descreverSolicitacao(Audit $audit, array $novos, ?Model $alvo): array
    {
        $contaId = $alvo?->conta_id ?? ($novos['conta_id'] ?? null);

        $contexto = implode(' · ', array_filter([
            'Solicitação #'.$audit->auditable_id,
            $contaId ? 'Conta '.$contaId : null,
            $alvo?->conta?->cliente?->name,
        ]));

        $extras = [];
        $titulo = 'Alterou a solicitação de limite';
        $variante = 'purple';

        if ($audit->event === 'created') {
            $titulo = 'Solicitou aumento de limite';
            $variante = 'warning';
        } elseif (($novos['status'] ?? null) === 'aprovada') {
            $titulo = 'Aprovou a solicitação de aumento de limite';
            $variante = 'success';
        } elseif (($novos['status'] ?? null) === 'recusada') {
            $titulo = 'Recusou a solicitação de aumento de limite';
            $variante = 'danger';
        }

        // na aprovação/recusa só o status muda; mostra também o valor pedido
        if ($audit->event === 'updated' && $alvo) {
            $extras[] = [
                'campo' => 'Limite solicitado',
                'antes' => null,
                'depois' => $this->formatar('limite', $alvo->limite),
            ];
        }

        return [
            'entidade' => 'Solicitação',
            'titulo' => $titulo,
            'contexto' => $contexto,
            'variante' => $variante,
            'extras' => $extras,
        ];
    }

    protected function descreverConta(Audit $audit, array $antigos, array $novos, ?Model $alvo): array
    {
        $contexto = implode(' · ', array_filter([
            'Conta '.$audit->auditable_id,
            $alvo?->cliente?->name,
        ]));

        $titulo = 'Alterou dados da conta';
        $variante = 'purple';

        if ($audit->event === 'created') {
            $titulo = 'Abriu uma conta';
            $variante = 'success';
        } elseif ($audit->event === 'deleted') {
            $titulo = 'Removeu a conta';
            $variante = 'danger';
        } elseif (array_key_exists('bloqueado', $novos)) {
            $bloqueou = (bool) $novos['bloqueado'];
            $titulo = $bloqueou ? 'Bloqueou a conta' : 'Desbloqueou a conta';
            $variante = $bloqueou ? 'danger' : 'success';
        } elseif (array_key_exists('limite', $novos)) {
            $aumentou = (float) $novos['limite'] > (float) ($antigos['limite'] ?? 0);
            $titulo = $aumentou ? 'Aumentou o limite da conta' : 'Reduziu o limite da conta';
        } elseif (array_key_exists('saldo', $novos)) {
            $titulo = 'Alterou o saldo da conta';
        }

        return [
            'entidade' => 'Conta',
            'titulo' => $titulo,
            'contexto' => $contexto,
            'variante' => $variante,
            'extras' => [],
        ];
    }

    protected function descreverUsuario(Audit $audit, array $antigos, array $novos, ?Model $alvo, array $perfis): array
    {
        $roleId = $alvo?->role_id ?? ($novos['role_id'] ?? $antigos['role_id'] ?? null);

        $perfil = match ($perfis[$roleId] ?? null) {
            'gerente_conta' => 'gerente de conta',
            'gerente_geral' => 'gerente geral',
            'cliente' => 'cliente',
            default => 'usuário',
        };

        $nome = $alvo?->name ?? ($novos['name'] ?? $antigos['name'] ?? '#'.$audit->auditable_id);
        $email = $alvo?->email ?? ($novos['email'] ?? $antigos['email'] ?? null);

        [$titulo, $variante] = match ($audit->event) {
            'created' => ['Cadastrou um '.$perfil, 'success'],
            'deleted' => ['Removeu o '.$perfil, 'danger'],
            default => ['Alterou os dados do '.$perfil, 'purple'],
        };

        return [
            'entidade' => 'Usuário',
            'titulo' => $titulo,
            'contexto' => implode(' · ', array_filter([$nome, $email])),
            'variante' => $variante,
            'extras' => [],
        ];
    }

    // Lista "campo: antes → depois" só com o que interessa
    protected function mudancas(string $evento, array $antigos, array $novos): array
    {
        if ($evento === 'deleted') {
            return [];
        }

        $campos = array_diff(
            array_unique(array_merge(array_keys($antigos), array_keys($novos))),
            self::OCULTOS
        );

        $linhas = [];

        foreach ($campos as $campo) {
            if ($evento === 'created' && ($novos[$campo] ?? null) === null) {
                continue;
            }

            $linhas[] = [
                'campo' => self::ROTULOS[$campo] ?? $campo,
                'antes' => array_key_exists($campo, $antigos)
                    ? $this->formatar($campo, $antigos[$campo])
                    : null,
                'depois' => $this->formatar($campo, $novos[$campo] ?? null),
            ];
        }

        return $linhas;
    }

    protected function formatar(string $campo, mixed $valor): string
    {
        if ($valor === null || $valor === '') {
            return '—';
        }

        return match ($campo) {
            'saldo', 'limite' => 'R$ '.number_format((float) $valor, 2, ',', '.'),
            'bloqueado' => $valor ? 'Bloqueada' : 'Ativa',
            'status' => ucfirst((string) $valor),
            default => is_array($valor)
                ? json_encode($valor, JSON_UNESCAPED_UNICODE)
                : (string) $valor,
        };
    }

    protected function paraArray(mixed $valor): array
    {
        if (is_string($valor)) {
            $valor = json_decode($valor, true);
        }

        return is_array($valor) ? $valor : [];
    }
}