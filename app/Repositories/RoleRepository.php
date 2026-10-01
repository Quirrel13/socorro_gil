<?php

namespace App\Repositories;

use App\Enums\NomeRole;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends BaseRepository
{
    public function __construct(protected Role $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }

    public function buscarPorNome(NomeRole $nome): ?Model
    {
        return $this->getModel()->newQuery()
            ->where('name', $nome->value)
            ->first();
    }
}