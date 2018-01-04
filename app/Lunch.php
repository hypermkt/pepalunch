<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lunch extends Model
{
    protected $fillable = ['lunch_at'];

    public function lunchUsers()
    {
        return $this->hasMany('App\LunchUser');
    }

    public function getMyLunches(int $myUserId, $lunchAt = null)
    {
        $lunchAt = $lunchAt ?? now();

        return DB::table('lunches')
            ->join('lunch_users', 'lunches.id', '=', 'lunch_users.lunch_id')
            ->where('lunch_users.user_id', $myUserId)
            ->where('lunches.lunch_at', '>=', $lunchAt)
            ->select('lunches.*')
            ->get();
    }
}
