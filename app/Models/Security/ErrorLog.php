<?php

namespace App\Models\Security;

use App\Models\CoreModel;

class ErrorLog extends CoreModel
{

    protected $fillable = [
        'description',
        'ip',
        'error',
        'parameters',
        'request',
        'user_id',
        'username',
        'path',
        'headers',
        'status_code',
    ];

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                //Insert rules to create
            ],
            'update' => [
                //Insert rules to update
            ],
        ];
        return $rules[$scenario] ?? [];
    }


}
