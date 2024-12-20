<?php

namespace App\Services\Security;

use App\Models\Security\User;
use App\Services\CoreService;

class UserService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }
}
