<?php

namespace App\Services\Security;

use App\Models\Security\User;
use App\Services\CoreService;
use Illuminate\Support\Facades\Log;
use Throwable;

class UserService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }

    public function create(array $params): array
    {
        return $this->handleUserOperation(fn() => parent::create($params), $params);
    }

    public function update(string $id, array $params): array
    {
        return $this->handleUserOperation(fn() => parent::update($id, $params), $params, $id);
    }

    private function handleUserOperation(callable $operation, array $params, ?string $id = null): array
    {
        try {
            $this->validateParams($params);
            $result = $operation();

            if ($result['success']) {
                return $this->syncRoles($result, $params['roles_id'] ?? []);
            }

            return $result;
        } catch (Throwable $e) {
            Log::error('User operation failed', [
                'error' => $e->getMessage(),
                'user_id' => $id,
                'params' => $params,
            ]);

            return [
                'success' => false,
                'message' => __('services.resource.operation_failed'),
                'errors' => [$e->getMessage()],
            ];
        }
    }

    private function syncRoles(array $result, array $rolesId): array
    {
        if (empty($rolesId)) {
            return $result;
        }

        try {
            $result['data']->roles()->sync($rolesId);
            Log::info('Roles synchronized successfully', [
                'user_id' => $result['data']->id,
                'roles' => $rolesId,
            ]);
        } catch (Throwable $e) {
            Log::error('Role synchronization failed', [
                'error' => $e->getMessage(),
                'user_id' => $result['data']->id,
                'roles' => $rolesId,
            ]);

            return [
                'success' => false,
                'message' => __('services.resource.role_sync_failed'),
                'errors' => [$e->getMessage()],
            ];
        }

        return $result;
    }

    private function validateParams(array $params): void
    {
        if (isset($params['roles_id']) && !is_array($params['roles_id'])) {
            throw new \InvalidArgumentException(__('services.resource.roles_id_must_be_array'));
        }
    }
}
