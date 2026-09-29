<?php

namespace App\Repositories;

use App\Models\Solicitacao;

class SolicitacaoRepository extends BaseRepository
{
    public function __construct(protected Solicitacao $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}