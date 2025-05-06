<?php

namespace App\Services\Security;

use App\Services\CoreService;
use App\Models\Security\Role;
use Throwable;

class RoleService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(Role::class);
    }

    public function create(array $params): array
    {
        $result = parent::create($params);

        if ($result['success']){
            $role = $result['data'];
            try {
                $role->routes()->sync($params['routes_id']);
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => __('services.failed'),
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $result;
    }

    public function update(string $id, array $params): array
    {
        $result = parent::update($id, $params);

        if ($result['success'] && $params['routes_id']){
            $role = $result['data'];
            try {
                $role->routes()->sync($params['routes_id']);
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => __('services.failed'),
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $result;
    }
}
