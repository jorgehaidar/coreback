<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Services\Security\ApiKeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;


class ApiKeyController extends CoreController
{
    public function __construct()
    {
        $this->service = new ApiKeyService();
    }

    public function generateApiKey(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $result = $this->service->generateApiKey($id);

            if ($result['success']) {
                DB::commit();
                return response()->json($result, 201);
            }

            DB::rollBack();
            return response()->json($result, 400);

        } catch (Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
