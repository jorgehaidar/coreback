<?php

namespace App\Models\Security;

use App\Models\CoreModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends CoreModel
{

    protected $relations = ['users'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'users_id',
        'date_time',
        'action_name',
        'ip',
        'record',
        'table_name'
    ];

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'users_id' => 'required|integer|exists:users,id',
                'date_time' => 'required|date',
                'action_name' => 'required|max:255',
                'ip' => 'required|max:16',
                'record' => 'required|max:255',
                'table_name' => 'required|max:255'
            ],
            'update' => [
                'users_id' => 'required|integer|exists:users,id',
                'date_time' => 'required|date',
                'action_name' => 'required|max:255',
                'ip' => 'required|max:16',
                'record' => 'required|max:255',
                'table_name' => 'required|max:255'
            ],
        ];
        return $rules[$scenario] ?? [];
    }
}
