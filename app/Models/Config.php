<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_config';


    public function getBannerAttribute($banner)
    {
        return array_values(json_decode($banner, true) ?: []);
    }

    public function setBannerAttribute($banner)
    {
        $this->attributes['banner'] = json_encode(array_values($banner));
    }

    public function getMessageAttribute($message)
    {
        return array_values(json_decode($message, true) ?: []);
    }

    public function setMessageAttribute($message)
    {
        $this->attributes['message'] = json_encode(array_values($message));
    }
}
