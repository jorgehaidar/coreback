<?php

namespace App\Http\Controllers\Security;

use Mbox\BackCore\Controllers\CoreController;
use App\Models\Security\Log;
use App\Services\Security\LogService;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class LogController extends CoreController
{
    public function __construct()
    {
        $this->service = new LogService();
    }
}
