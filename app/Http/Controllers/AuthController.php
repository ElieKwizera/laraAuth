<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register( RegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create(
            [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password'])
            ]
        );

        $token = $user->createToken('authenticationtoken')->plainTextToken;

        $response = [
            'success'=>true,
            'data'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }

    public function login(Request $request)
    {
        $body = $request->validate([
            'email'=>'required|min:6',
            'password'=>'required|min:6'
        ]);

        $user = User::where('email',$body['email'])->first();

        if(!$user || !Hash::check($body['password'],$user->password))
        {
            return response(['message'=>'Login failed. Please check your email and password'],401);
        }

        $token = $user->createToken('authenticationtoken')->plainTextToken;

        $response = [
            'success'=>true,
            'data'=>$user,
            'token'=>$token
        ];
        return response($response,200);
    }

    public function getUser(Request $request)
    {
        $user = auth()->user();

        $response = [
            'success'=>true,
            'data'=>$user,
        ];
        return response($response,200);

    }
}
