<?php

namespace App\Repositories;

use App\Models\Pix;

class PixRepository extends BaseRepository
{
    public function __construct(protected Pix $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}