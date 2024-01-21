<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Pivot
{
    use HasFactory;
    // User model
    public function roles()
    {
        return $this->belongsToMany(Role::class)->using(RoleUser::class);
    }

    // Role model
    public function users()
    {
        return $this->belongsToMany(User::class)->using(RoleUser::class);
    }

}
