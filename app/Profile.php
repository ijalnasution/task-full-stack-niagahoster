<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public function credential()
    {
        return $this->hasOne('App\User');
    }
}
