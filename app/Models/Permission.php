<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'route',
        'params',
        'method',
        'ctrl_path',
        'ctrl_name',
        'ctrl_action',
        'type',
        'guard_name',
        'description',    
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }
}
