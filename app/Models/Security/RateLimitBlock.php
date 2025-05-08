<?php

namespace App\Models\Security;

use Mbox\BackCore\Models\CoreModel;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string|null $key)
 * @method static orderBy(string $string, string $string1)
 */
class RateLimitBlock extends CoreModel
{
    protected $fillable = [
        'key',
        'level',
        'blocked_until',
        'active'
    ];

    protected $casts = [
        'blocked_until' => 'datetime',
        'active' => 'boolean'
    ];
}
