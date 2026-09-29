<?php

namespace App\Repositories;

use App\Models\TipoInvestimento;

class TipoInvestimentoRepository extends BaseRepository
{
    public function __construct(protected TipoInvestimento $model)
    {
    }

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}