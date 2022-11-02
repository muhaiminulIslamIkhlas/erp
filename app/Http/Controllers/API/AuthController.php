<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('name', 'password'))) {
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;

            return [
                'access_token' => $token,
            ];
        }

        return response()->json([
            'message' => 'Invalid Credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        try {
            $user = Auth::user()->token();
            // // echo $user;
            $user->revoke();
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
