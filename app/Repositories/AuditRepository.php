<?php

namespace App\Repositories;

use App\Enums\NomeRole;
use App\Models\Conta;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use OwenIt\Auditing\Models\Audit;

class AuditRepository extends BaseRepository
{
    public function __construct(protected Audit $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    /**
     * Atividades de quem opera o sistema: gerentes de conta e gerente geral
     * (aprovações/recusas). Ações feitas pelo cliente (ex.: saldo após um Pix)
     * ficam de fora.
     */
    public function listarAtividadesDeGerentes(int $limite = 15)
    {
        $autores = User::withTrashed()
            ->whereHas('role', function ($query) {
                $query->whereIn('name', [
                    NomeRole::GERENTE_CONTA->value,
                    NomeRole::GERENTE_GERAL->value,
                ]);
            })
            ->select('id');

        return $this->getModel()->newQuery()
            ->where('user_type', (new User())->getMorphClass())
            ->whereIn('user_id', $autores)
            ->whereIn('auditable_type', [
                (new User())->getMorphClass(),
                (new Conta())->getMorphClass(),
                (new Solicitacao())->getMorphClass(),
            ])
            ->with([
                'user',
                'auditable' => function (MorphTo $morph) {
                    $morph->withTrashed()->morphWith([
                        Conta::class => ['cliente' => fn ($query) => $query->withTrashed()],
                        Solicitacao::class => ['conta.cliente'],
                    ]);
                },
            ])
            ->latest()
            ->paginate($limite);
    }
}