<?php

namespace App\Http\Controllers;

use App\Services\CoreService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

abstract class CoreController
{
    protected CoreService $service;

    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->all();

            $result = $this->service->getAll($params);

            return response()->json([
                'success' => true,
                'message' => 'Resources retrieved successfully.',
                'data' => $result,
            ], 200);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the resources.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $params = $request->all();
            $result = $this->service->create($params);

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

    public function show(string $id, Request $request): JsonResponse
    {
        try {
            $params = $request->all();
            $result = $this->service->getById($id, $params);
            return response()->json($result, $result['status']);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while retrieving the resource.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    public function update(Request $request, string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $params = $request->all();
            $result = $this->service->update($id, $params);

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

    public function destroy(string $id): JsonResponse
    {
        try {
            $result = $this->service->deleteById($id);
            return response()->json($result, $result['status']);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the resource.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            $result = $this->service->deleteMultiple($request->all());

            return response()->json($result, $result['status']);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the resources.',
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }
}
