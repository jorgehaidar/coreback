<?php

namespace App\Services\Security;

use Mbox\BackCore\Models\Security\Route;
use Mbox\BackCore\Services\CoreService;

class RouteService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(Route::class);
    }
}
