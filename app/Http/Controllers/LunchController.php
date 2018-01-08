<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Lunch;
use App\LunchUser;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class LunchController extends Controller
{
    public function index()
    {
        $lunch = new Lunch();
        $lunches = $lunch->getMyLunches(1);// TODO: JWTから取得したユーザーIDに変更する

        foreach ($lunches as $lunch) {
            $lunchUsers = LunchUser::where('lunch_id', $lunch->id)->get();
            $users = [];
            foreach ($lunchUsers as $lunchUser) {
                $users[] = $lunchUser->user()->first();
            }
            $lunch->users = $users;
        }

        return $lunches;
    }

    public function store()
    {
        $lunch = \LunchMatch::shuffleLunch(1); // TODO: JWTから取得したユーザーIDに変更する
        if (!$lunch) {
            throw new BadRequestHttpException('Failed to store lunch', null, 400);
        }

        return response()
            ->json($lunch)
            ->setStatusCode(201);
    }
}
