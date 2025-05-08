<?php

namespace App\Models\Security;

use Mbox\BackCore\Models\CoreModel;

class Permission extends CoreModel
{

    protected $fillable = [
        'roles_id',
        'routes_id'
    ];

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'roles_id' => 'required|exists:roles,id',
                'routes_id.*' => 'required|integer|exists:routes,id',
            ],
            'update' => [
                'roles_id' => 'required|exists:roles,id',
                'routes_id.*' => 'required|integer|exists:routes,id',
            ],
        ];
        return $rules[$scenario] ?? [];
    }
}
