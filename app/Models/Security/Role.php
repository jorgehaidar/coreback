<?php

namespace App\Models\Security;

use Mbox\BackCore\Models\CoreModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Mbox\BackCore\Models\Security\Route;

class Role extends CoreModel
{

    protected $fillable = [
        'name',
        'description',
        'active'
    ];

    protected $relations = ['users', 'routes'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function routes(): BelongsToMany
    {
        return $this->belongsToMany(Route::class, 'permissions', 'roles_id', 'routes_id');
    }

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'name' => 'required|max:255',
                'description' => 'nullable|max:255',
                'active' => 'required|boolean',
                'routes_id.*' => 'required|integer|exists:routes,id'
            ],
            'update' => [
                'name' => 'required|max:255',
                'description' => 'nullable|max:255',
                'active' => 'required|boolean',
                'routes_id.*' => 'required|integer|exists:routes,id'
            ],
        ];
        return $rules[$scenario] ?? [];
    }
}
