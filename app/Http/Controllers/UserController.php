<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function show(Request $request, $id)
    {
         return response()
            ->json(User::find($id))
            ->setStatusCode(200);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $data = $request->only(['wanted_lunch']);

        return response()
            ->json($user->update($data))
            ->setStatusCode(204);
    }
}
