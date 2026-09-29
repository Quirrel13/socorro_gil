<?php

namespace App\Services;

use App\Repositories\InvestimentoRepository;

class InvestimentoService extends BaseService
{
    public function __construct(protected InvestimentoRepository $repository)
    {
    }

    protected function getRepository(): mixed
    {
        return $this->repository;
    }
}