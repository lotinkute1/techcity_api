<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{ 
    public function login(LoginRequest $request){
       if( Auth::attempt([
        'email'=>$request->email,
        'password'=>$request->password
        ])){
           $user= User::where('email',$request->email)->first();
           $user->token=$user->createToken('Token')->accessToken;
           return response()->json($user);
       }
       return response()->json(['message'=>'Tài khoản hoặc mật khẩu không đúng'],401);
    }

    public function checkAuth(Request $request){
        return response()->json($request->user('api'));
    }
    public function logout(Request $request){
       if($request->user()->token()->revoke()){
        return response()->json(['message'=>'logout success!']);
       }
       return response()->json(['message'=>'logout failed!'],401);
    }

}
