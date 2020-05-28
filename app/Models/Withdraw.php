<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_withdraw';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

}
