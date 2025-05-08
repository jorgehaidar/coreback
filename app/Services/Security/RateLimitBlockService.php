<?php

namespace App\Services\Security;

use App\Models\Security\User;
use Mbox\BackCore\Services\CoreService;

class RateLimitBlockService extends CoreService
{
    public function __construct()
    {
        $this->modelClass = resolve(User::class);
    }
}
