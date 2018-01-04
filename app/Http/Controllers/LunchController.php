<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lunch;
use App\LunchUser;

class LunchController extends Controller
{
    public function index()
    {
        $lunches = DB::table('lunches')
            ->join('lunch_users', 'lunches.id', '=', 'lunch_users.lunch_id')
            ->where('lunch_users.user_id', 1) // TODO: JWTから取得したユーザーIDに変更する
            ->where('lunches.lunch_at', '>=', now())
            ->select('lunches.*')
            ->get();

        foreach ($lunches as $lunch) {
            $lunchUsers = LunchUser::where('lunch_id', $lunch->id)->get();
            $users = [];
            foreach ($lunchUsers as $lunchUser) {
                $users[] = $lunchUser->user()->first()->toArray();
            }
            $lunch->users = $users;
        }

        return $lunches;
    }
}
