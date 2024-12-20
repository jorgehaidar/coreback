<?php

namespace App\Services\Security;

use App\Models\Security\User;
use App\Services\CoreService;

class RateLimitBlockService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }
}
