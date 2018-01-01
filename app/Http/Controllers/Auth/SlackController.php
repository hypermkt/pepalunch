<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Socialite;

class SlackController extends Controller
{
    public function redirectToSlackProvider()
    {
        return Socialite::with('slack')->redirect();
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    public function handleSlackProviderCallback()
    {
       try {
            $slackUser = Socialite::driver('slack')->user();
            $authUser = $this->findOrCreateUser($slackUser);

            Auth::login($authUser);

       } catch (Exception $e) {

       }
       return redirect('/');
    }

    private function findOrCreateUser($slackUser) {
        $authUser = User::where('slack_id', $slackUser->accessTokenResponseBody['user']['id'])->first();
        if ($authUser) {
            return $authUser;
        }

        return User::create([
            'name' => $slackUser->accessTokenResponseBody['user']['name'],
            'slack_id' => $slackUser->accessTokenResponseBody['user']['id'],
            'slack_access_token' => $slackUser->accessTokenResponseBody['access_token']
        ]);
    }
}
