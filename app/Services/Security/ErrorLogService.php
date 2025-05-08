<?php

namespace App\Services\Security;

use App\Models\Security\ErrorLog;
use Mbox\BackCore\Services\CoreService;

class ErrorLogService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(ErrorLog::class);
    }
}
