<?php

namespace App\Services\Security;

use App\Models\Security\ApiKey;
use App\Models\Security\User;
use App\Services\CoreService;
use Throwable;

class ApiKeyService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(ApiKey::class);
    }

    public function generateApiKey(string $userId): array
    {
        try {
            $userService = new UserService();
            $result = $userService->getById($userId);
            if (!$result['success']){
                return $result;
            }

            $apiKey = ApiKey::generateKey();

            $result = $this->create([
                'key' => $apiKey,
                'users_id' => $userId,
                'status' => true
            ]);

            return [
                'success' => true,
                'message' => 'Resource created successfully.',
                'data' => $result['data'],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'message' => 'Failed to create resource.',
                'errors' => [$e->getMessage()],
            ];
        }
    }
}
