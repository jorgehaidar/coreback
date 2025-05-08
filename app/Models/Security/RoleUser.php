<?php

namespace App\Models\Security;

use Mbox\BackCore\Models\CoreModel;

class RoleUser extends CoreModel
{

    protected $table = 'role_user';

    protected $fillable = [
        'user_id',
        'role_id'
    ];

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'user_id' => 'required|integer|exists:users,id',
                'role_id.*' => 'required|integer|exists:roles,id',
            ],
            'update' => [
                'user_id' => 'required|integer|exists:users,id',
                'role_id.*' => 'required|integer|exists:roles,id',
            ],
        ];
        return $rules[$scenario] ?? [];
    }
}
