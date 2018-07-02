<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'created_at', 'updated_at'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function pages()
    {
        return $this->hasMany('App\Page');
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            if (!$this->hasAnyRole($roles)) {
                return false;
//                dd('need redirect 1');
//                Auth::logout();
//                return redirect('/');
//                abort(401, 'This action is unauthorized.');
            }
        }
        if (!$this->hasRole($roles)) {
//            dd('need redirect 2');
            return false;
//            Auth::logout();
//            return redirect('/login ');
//            abort(401, 'This action is unauthorized.');
        }
        return true;
    }
    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles)
    {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }
    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role)
    {
        return null !== $this->roles()->where('name', $role)->first();
    }
    
}
