<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        request()->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        if(Auth::attempt(['email' => request()->email, 'password' => request()->password], request()->get('remember'))){
            $token = auth()->user()->createToken('Task')->accessToken;
            return response()->json(['status' => true,'token' => $token], 201);
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid Credentials'
        ],422);
    }
}
