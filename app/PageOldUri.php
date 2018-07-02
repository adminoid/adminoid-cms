<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PageOldUri extends Model
{

    protected $fillable = ['uri'];

    public function page()
    {
        return $this->belongsTo('App\Page');
    }
}
