<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Safeguard extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_safeguard';

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
