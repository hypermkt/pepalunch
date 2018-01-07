<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Socialite;
use Tymon\JWTAuth\JWTAuth;

class SlackController extends Controller
{
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function redirectToSlackProvider()
    {
        return Socialite::with('slack')->stateless()->redirect();
    }

    public function logout()
    {
        // TODO
    }

    public function handleSlackProviderCallback()
    {
       try {
            $slackUser = Socialite::driver('slack')->stateless()->user();
            $user = $this->findOrCreateUser($slackUser);

            $token = $this->jwt->fromUser($user);

           return response()->json(compact('authUser', 'token'));
       } catch (Exception $e) {

       }
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
