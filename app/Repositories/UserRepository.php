<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(protected User $model) {}

    protected function getModel(): mixed
    {
        return $this->model->newInstance();
    }
}