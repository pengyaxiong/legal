<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //黑名单为空
    protected $guarded = [];
    protected $table = 'mini_article_category';

    public function articles()
    {
        return $this->hasMany(Article::class,'category_id');
    }
}
