<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
            $user = Socialite::driver('slack')->user();
            var_dump($user);

       } catch (Exception $e) {

       }
    }
}
