<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Socialite;

class SlackController extends Controller
{
    public function redirectToSlackProvider()
    {
        return Socialite::with('slack')->redirect();
    }

    public function handleSlackProviderCallback()
    {
       try {
            $slackUser = Socialite::driver('slack')->user();

            $countOfUser= User::where('slack_id', $slackUser->accessTokenResponseBody['user']['id'])->count();
            if ($countOfUser === 0) {
                $user = new User();
                $user->name = $slackUser->accessTokenResponseBody['user']['name'];
                $user->slack_id = $slackUser->accessTokenResponseBody['user']['id'];
                $user->slack_access_token = $slackUser->accessTokenResponseBody['access_token'];
                $user->save();
            }
       } catch (Exception $e) {

       }
       return redirect('/');
    }
}
