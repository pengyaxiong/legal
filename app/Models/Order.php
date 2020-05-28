<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_order';

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function safeguard()
    {
        return $this->belongsTo(Safeguard::class);
    }
}
