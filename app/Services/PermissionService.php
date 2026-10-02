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
        session([
            'user_permissions'      => $this->getPermissions($role),
            'user_permissions_role' => $role,
        ]);
    }

    public function isAuthorized($resource, $user) {
        $permissions = ((int) session('user_permissions_role') === (int) $user->role_id)
            ? session('user_permissions')
            : ($this->memo[$user->role_id] ??= $this->getPermissions($user->role_id));

        return isset($permissions[$resource]);
    }
}
