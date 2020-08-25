<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * Get the users that owns by the role.
     */
    public function users()
    {
        return $this->hasMany('App\Role');
    }
}
