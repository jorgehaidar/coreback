<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Services\Security\ErrorLogService;

class ErrorLogController extends CoreController
{
    public function __construct()
    {
        $this->service = new ErrorLogService();
    }
}
