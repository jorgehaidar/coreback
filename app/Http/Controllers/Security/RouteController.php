<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Services\Security\RouteService;

class RouteController extends CoreController
{
    public function __construct()
    {
        $this->service = new RouteService();
    }
}
