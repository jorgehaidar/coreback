<?php

namespace App\Http\Controllers\Security;

use Mbox\BackCore\Controllers\CoreController;
use App\Models\Security\User;
use App\Services\Security\UserService;

class UserController extends CoreController
{
    public function __construct()
    {
        $this->service = new UserService();
    }
}
