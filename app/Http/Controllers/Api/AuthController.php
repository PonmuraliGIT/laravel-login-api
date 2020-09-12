<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Unique;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = request(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return response()->json(
                ['message' => 'invalid email or password'],
                401
            );
        }
        $user = $request->user();
        $token = $user->createToken('Access Token');
        $user->access_token = $token->accessToken;
        return response()->json([
            'users' => $user
        ], 201);
    }
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|Unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $user->save();

        return response()->json([
            "message" => "User Registered Successfully"
        ], 201);
    }
    public function index()
    {
        echo "Hello Android!!!";
    }
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => "User Loggedout Successfully"

        ], 200);
    }
}
