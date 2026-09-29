<?php

namespace App\Repositories;

use App\Models\Investimento;

class InvestimentoRepository extends BaseRepository
{
    public function __construct(protected Investimento $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}