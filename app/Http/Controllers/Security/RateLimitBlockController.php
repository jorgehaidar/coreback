<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Models\Security\RateLimitBlock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class RateLimitBlockController extends CoreController
{
    public function index(Request $request): JsonResponse
    {
        $blocks = RateLimitBlock::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $blocks,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $block = RateLimitBlock::where('key', $id)->first();

        if ($block) {
            $block->update(['active' => false, 'level' => 0, 'blocked_until' => null]);

            return response()->json([
                'success' => true,
                'message' => "Block for {$id} has been removed.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "No block found for {$id}.",
        ], 404);
    }
}
