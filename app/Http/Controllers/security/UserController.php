<?php

namespace App\Http\Controllers\security;

use App\Http\Controllers\CoreController;
use App\Models\security\User;
use App\Services\security\UserService;

class UserController extends CoreController
{
    public function __construct()
    {
        $this->service = new UserService();
    }
}
