<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = [
        'id', 'brand_id', 'description', 'link'
    ];

    public function photos()
    {
        return $this->hasMany('App\Photos');
    }

    public function optionals()
    {
        return $this->hasMany('App\Optionals');
    }

    public function brand()
    {
        return $this->belongsTo('App\Brand');
    }
}
