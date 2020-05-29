<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileOrder extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_mobile_order';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
