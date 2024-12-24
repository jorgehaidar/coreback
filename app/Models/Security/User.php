<?php

namespace App\Models\Security;

use App\Models\CoreModel;
use App\Notifications\CustomVerifyEmail;
use App\Rules\StrongPassword;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends CoreModel implements JWTSubject, CanResetPasswordContract,
    AuthenticatableContract,
    AuthorizableContract,
    \Illuminate\Contracts\Auth\MustVerifyEmail
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $relations = ['roles', 'logs'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(Log::class);
    }

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
                'email' => 'required|email|max:255|unique:users,email',
                'password' => ['required', new StrongPassword()],
            ],
            'update' => [
                'name' => 'required|string',
                'email' => 'required|email|max:255|unique:users,email,'.$this->id,
                'password' => ['required', new StrongPassword()],
            ],
        ];

        return $rules[$scenario] ?? [];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function hasAccessToRoute(string $currentRoute, string $currentMethod): bool
    {
        return $this->roles()
            ->whereHas('routes', function ($query) use ($currentRoute, $currentMethod){
                $query->where([
                    'route' => $currentRoute,
                    'method' => $currentMethod
                ]);
            })
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->roles()
            ->where(['name' => 'admin'])
            ->exists();
    }

    public function getPermissions()
    {
        $permissions = $this->roles()
            ->with('routes')
            ->get()
            ->flatMap(function ($role) {
                return $role->routes;
            });

        return $permissions->groupBy('menu_module')
            ->map(function ($moduleGroup) {
                return $moduleGroup->groupBy('module')
                    ->map(function ($actionGroup) {
                        return $actionGroup->pluck('action');
                    });
            });
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new CustomVerifyEmail());
    }
}
