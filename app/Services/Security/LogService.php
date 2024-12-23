<?php

namespace App\Services\Security;

use App\Services\CoreService;
use App\Models\Security\Log;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class LogService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(Log::class);
    }

    static public function createLoginLogout($action)
    {
        $model = Log::create([
            'user_id' => auth()->check() ? auth()->user()->id : 1,
            'date_time' => Carbon::now(),
            'action_name' => $action,
            'ip' => \request()->ip(),
            'record' => 'login',
            'table_name' => 'User',
        ]);

        if (!$model){
            return response()->json([
                'message' => 'Error while creating Log'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }

    static public function createAny($action, $table_name, $record)
    {
        $model = Log::create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'date_time' => Carbon::now(),
            'action_name' => $action,
            'ip' => \request()->ip(),
            'record' => $record,
            'table_name' => $table_name,
        ]);

        if (!$model){
            return response()->json([
                'message' => 'Error while creating Log'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }
}
