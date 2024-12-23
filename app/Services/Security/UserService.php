<?php

namespace App\Services\Security;

use App\Models\Security\User;
use App\Services\CoreService;
use Throwable;

class UserService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }

    public function create(array $params): array
    {
        $result = parent::create($params);

        if ($result['success']){
            $role = $result['data'];
            try {
                $role->roles()->sync($params['roles_id']);
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => 'Failed to create resource.',
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $result;
    }

    public function update(string $id, array $params): array
    {
        $result = parent::update($id, $params);
        if ($result['success'] && $params['roles_id']){
            $role = $result['data'];
            try {
                $role->roles()->sync($params['roles_id']);
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => 'Failed to create resource.',
                    'errors' => [$e->getMessage()],
                ];
            }
        }

        return $result;
    }
}
