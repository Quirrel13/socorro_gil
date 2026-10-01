<?php

namespace App\Services;

use App\Repositories\PermissionRepository;

class PermissionService extends BaseService {

    public function __construct(protected PermissionRepository $repository) {}

    protected array $memo = [];

    protected function getRepository(): mixed {
        return $this->repository;
    }

    public function getPermissions($role) {
        $arr = Array();
        $perm = $this->repository->list(['resource'], ['field' => 'role_id', 'value' => $role], 'resource_id');

        foreach($perm as $item) {
            $arr[$item->resource->name] = true;
        }

        return $arr;
    }

    public function loadPermissions($role) {

        $arr_permissions = $this->getPermissions($role);

        session(['user_permissions' => $arr_permissions]);
    }

    public function isAuthorized($resource, $user) {

        $permissions = session('user_permissions');

        // ALTERADO: guarda em memória para não consultar o banco a cada checagem
        if(!isset($permissions)) {
            $permissions = $this->memo[$user->role_id] ??= $this->getPermissions($user->role_id);
        }

        if(array_key_exists($resource, $permissions)) {
            return true;
        }
        return false;
    }
}
