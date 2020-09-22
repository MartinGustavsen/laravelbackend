<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validatedData=$request->validate([
            'name'=>'required|max:55',
            'email'=>'email|required',
            'password'=>'required|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user);


        // $user = User::create($validatedData);
        // $accessToken = $user->createToken('userToken')->accessToken;

        // return response(['user'=>$user,'access_token'=>$accessToken]);
    }

    public function login(Request $request){

        $loginData=$request->validate([
            'email'=>'email|required',
            'password'=>'required'
        ]);

        if(!Auth::attempt($loginData)){
            return response(['message'=>'invalid user']);
        }
        $user = Auth::user();

        $token = $user->createToken($user->email.'-'.now());

        return response()->json([
            'token' => $token->accessToken
        ]);
        // $accessToken = Auth::user()->createToken('userToken')->access_token;

        // return response(['user'=>auth()->user(),'access_token'=>$accessToken]);
    }
}
