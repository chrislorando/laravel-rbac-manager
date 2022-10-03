<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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


    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function hasPermission($permission) 
    {
        // return $this::with([
        //     'roles' => function($query) use ($permission) {
        //         $query->select('id');
        //         $query->with(['permissions' => function($query) use ($permission) {
        //             $query->select('id');
        //             $query->where('name',$permission);
        //         }]);
        // }])->first() ?: false;

        return $this::with([
            'role' => function($query) use ($permission) {
                $query->select('id','name');
                $query->with(['permissions' => function($query) use ($permission) {
                    $query->select('id','name');
                    $query->where('name',$permission);
                }]);
        }])->first() ?: false;
    }
    
    public function teams()
    {
        // return $this->belongsToMany(Permission::class, 'role_permissions')
        //     ->using(Role::class)
        //     ->as('role')
        //     ->withPivot('role');

        return $this->hasManyThrough(
            PermissionRole::class,
            RoleUser::class,
            'role_id', // Foreign key on the environments table...
            'user_id', // Foreign key on the deployments table...
            'id', // Local key on the projects table...
            'id' // Local key on the environments table...
        );
    }
}
