<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    //
    public function signup(Request $request){

        //create user
        $request->validate([
            'password'=>'required|confirmed',
        ]);
        $user = new User();
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = $request -> password;

        $user->save();

        //create Token (token is a key we use to access api)
        $token = $user -> createToken('myToken')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'User logged out']);
    }

    public function login(Request $request){
        //check email
        $user = User::where('email',$request->email)->first();

        //check password
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message'=>'Bad login'],401);
        }

        //create token
        $token = $user -> createToken('myToken')->plainTextToken;
        return response()->json([
            'user'=>$user,
            'token'=>$token,
        ]);
    }

    //"token": "1|Gzz42h1Fd1F59B5xnT5lpgluh3IvlwqcqcPNDgXK"
}
