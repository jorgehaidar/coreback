<?php

namespace App\Services\Security;

use App\Services\CoreService;
use App\Models\Security\Route;

class RouteService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(Route::class);
    }
}
