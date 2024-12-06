<?php

namespace App\Models\security;

use App\Models\CoreModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;

class User extends CoreModel
{
    use
        HasFactory,
        Notifiable,
        CanResetPassword,
        Authenticatable,
        Authorizable,
        CanResetPassword,
        MustVerifyEmail;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rules($scenario = 'create'): array
    {
        $rules = [
            'create' => [
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required',
            ],
            'update' => [
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required',
            ],
        ];

        return $rules[$scenario] ?? [];
    }


}
