<?php

namespace App\Services\Security;

use Mbox\BackCore\Services\CoreService;
use App\Models\Security\Log;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Lang;

class LogService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(Log::class);
    }

    /**
     * Crea un log de login/logout
     */
    public static function createLoginLogout($action)
    {
        $model = Log::create([
            'user_id' => auth()->check() ? auth()->user()->id : 1,
            'date_time' => Carbon::now(),
            'action_name' => $action,
            'ip' => \request()->ip(),
            'record' => 'login',
            'table_name' => 'User',
        ]);

        if (!$model) {
            return response()->json([
                'message' => __('services.resource.log_create_error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }

    /**
     * Crea un log general
     */
    public static function createAny($action, $table_name, $record)
    {
        $model = Log::create([
            'user_id' => auth()->check() ? auth()->user()->id : null,
            'date_time' => Carbon::now(),
            'action_name' => $action,
            'ip' => \request()->ip(),
            'record' => $record,
            'table_name' => $table_name,
        ]);

        if (!$model) {
            return response()->json([
                'message' => __('services.resource.log_create_error'),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return true;
    }
}
