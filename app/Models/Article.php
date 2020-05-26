<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_article';

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
