<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'id', 'description',
    ];

    public function Cars()
    {
        return $this->hasMany('App\Car');
    }
}
