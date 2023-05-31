<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;

class ApiAuthController extends Controller
{
    public function login(LoginRequest $request){
        $user = User::where('username', $request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json([
                'message' => 'user atau password salah'
            ],401);
        }

        $token=$user->createToken('token')->plainTextToken;

        return new LoginResource([
            'message' => 'Success Login',
            'user'=>$user,
            'token'=>$token,
        ],200);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return response()->noContent();
    }
}