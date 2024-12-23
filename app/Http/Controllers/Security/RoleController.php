<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\CoreController;
use App\Services\Security\RoleService;

class RoleController extends CoreController
{
    public function __construct()
    {
        $this->service = new RoleService();
    }
}
