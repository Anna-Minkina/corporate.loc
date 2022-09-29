<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'login',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function articles()
    {

        return $this->hasMany('App\Models\Article');
    }

    public function roles()
    {

        return $this->belongsToMany('App\Models\Role', 'role_user');
    }


    public function canDo($permission, $require = FALSE)
    {

        if (is_array($permission)) {

            foreach ($permission as $perName) {

                $perName = $this->canDo($perName);
                if ($perName && !$require) {
                    return TRUE;
                } else if (!$perName && $require) {
                    return FALSE;
                }
            }
            return $require;
        } else {

            foreach ($this->roles as $role) {
                foreach ($role->perms as $perm) {
                    if ($permission == $perm->name) {

                        return TRUE;
                    }
                }
            }
        }
    }

    public function hasRole($name, $require = FALSE)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);
                if ($hasRole && !$require) {
                    return TRUE;
                } else if (!$hasRole && $require) {
                    return FALSE;
                }
            }
            return $require;
        } else {
            foreach ($this->roles as $role) {

                if ($role->name == $name) {
                    return TRUE;
                }
            }
        }

        return false;
    }
}
