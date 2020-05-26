<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lighthouse extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_lighthouse';

    public function getTypeAttribute($type)
    {
        return array_values(json_decode($type, true) ?: []);
    }

    public function setTypeAttribute($type)
    {
        $this->attributes['type'] = json_encode(array_values($type));
    }


}
