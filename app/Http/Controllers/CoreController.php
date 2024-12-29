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

    /**
     * @throws Throwable
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->all();

        $result = $this->service->getAll($params);

        return response()->json([
            'success' => true,
            'message' => 'Resources retrieved successfully.',
            'data' => $result,
        ]);
    }

    /**
     * @throws Throwable
     */
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
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function show(string $id, Request $request): JsonResponse
    {
        $params = $request->all();
        $result = $this->service->getById($id, $params);
        return response()->json($result, $result['status']);
    }

    /**
     * @throws Throwable
     */
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
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            DB::beginTransaction();
            $result = $this->service->deleteById($id);

            if ($result['success']) {
                DB::commit();
                return response()->json($result, 201);
            }

            DB::rollBack();
            return response()->json($result, 400);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            $result = $this->service->deleteMultiple($request->all());

            if ($result['success']) {
                DB::commit();
                return response()->json($result, 201);
            }

            DB::rollBack();
            return response()->json($result, 400);
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
