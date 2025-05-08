<?php

namespace App\Models\Security;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mbox\BackCore\Models\CoreModel;
use Random\RandomException;

class ApiKey extends CoreModel
{

    protected $relations = ['users'];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $fillable = [
        'key', 'users_id', 'status'
    ];

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'key' => 'required|string|unique:api_keys,key',
                'users_id' => 'required|integer|exists:users,id',
                'status' => 'required|boolean'
            ],
            'update' => [
                'key' => 'required|string|unique:api_keys,key,'.$this->id,
                'users_id' => 'required|integer|exists:users,id',
                'status' => 'required|boolean'
            ],
        ];
        return $rules[$scenario] ?? [];
    }

    /**
     * Generates a 32-character key
     * @return string
     * @throws RandomException
     */
    public static function generateKey(): string
    {
        return bin2hex(random_bytes(16));
    }
}
