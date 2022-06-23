<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    /**
     * login user
     *
     * @param AuthRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->validated();
        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'The provided credentials are incorrect.'], 401);
        }

        $token = Auth::user()->createToken('auth_token');
        return response()->json(['token' => $token->plainTextToken], 200);
    }
}
