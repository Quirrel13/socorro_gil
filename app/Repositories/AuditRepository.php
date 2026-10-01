<?php

namespace App\Repositories;

use App\Enums\NomeRole;
use App\Models\Conta;
use App\Models\Solicitacao;
use App\Models\User;
use OwenIt\Auditing\Models\Audit;

class AuditRepository extends BaseRepository
{
    public function __construct(protected Audit $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function listarAtividadesDeGerentes(int $limite = 15)
    {
        $gerentes = User::withTrashed()
            ->whereHas('role', fn ($q) => $q->where('name', NomeRole::GERENTE_CONTA->value))
            ->select('id');

        return $this->getModel()->newQuery()
            ->where('user_type', (new User())->getMorphClass())
            ->whereIn('user_id', $gerentes)
            ->whereIn('auditable_type', [
                (new User())->getMorphClass(),
                (new Conta())->getMorphClass(),
                (new Solicitacao())->getMorphClass(),
            ])
            ->with('user')
            ->latest()
            ->paginate($limite);
    }
}