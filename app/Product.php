<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeFindByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
