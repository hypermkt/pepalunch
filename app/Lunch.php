<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lunch extends Model
{
    protected $fillable = ['lunch_at'];

    public function lunchUsers()
    {
        return $this->hasMany('App\LunchUser');
    }
}
