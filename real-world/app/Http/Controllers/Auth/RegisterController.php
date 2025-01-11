<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Http\Controllers\Controller;
use App\Models\User;

class RegisterController extends Controller
{
    public function __invoke(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => ($request->bio)? $request->bio : null,
            'image' => ($request->image)? $request->image : null,
        ]);


        $token = Auth::login($user);

        return response()->json([
            'user' => [
                'email' => $user->email,
                'token' => $token,
                'username' => $user->username,
                'bio' => ($user->bio) ? $user->bio: null,
                'image' => $user->image ? $user->image : null,
            ]
        ]);

    }

}
