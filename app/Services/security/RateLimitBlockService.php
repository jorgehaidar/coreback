<?php

namespace App\Services\security;

use App\Models\security\User;
use App\Services\CoreService;

class RateLimitBlockService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }
}
