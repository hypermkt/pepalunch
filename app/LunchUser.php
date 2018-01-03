<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LunchUser extends Model
{
    protected $fillable = ['lunch_id', 'user_id'];

    public function lunch()
    {
        return $this->belongsTo('App\Lunch');
    }
}
