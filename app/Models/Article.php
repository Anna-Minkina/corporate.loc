<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = ['title', 'img', 'alias', 'text', 'desc', 'category_id']; 
    
    public function user() {

        return $this->belongsTo('App\Models\User');

    }

    public function category() {

        return $this->belongsTo('App\Models\Category');
    }

    public function comments()
    {

        return $this->hasMany('App\Models\Comment');
    }

   
}
