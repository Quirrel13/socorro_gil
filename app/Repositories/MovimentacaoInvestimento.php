<?php

namespace App\Repositories;

use App\Models\MovimentacaoInvestimento;

class MovimentacaoInvestimentoRepository extends BaseRepository
{
    public function __construct(protected MovimentacaoInvestimento $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}