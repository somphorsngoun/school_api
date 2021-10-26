<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class Usercontroller extends Controller
{
    public function signup(Request $request){

        // $request->validate([
        //     'password'=>'required|confirmed',
        // ]);
        // Create User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        //Create Token
        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=> $user,
            'token'=> $token
        ]);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(['message'=>'User logged out']);
    }

    public function login(Request $request){

        // Check email
        $user = User::where('email', $request->email)->first();
        // Check password
        if (!$user || !Hash::check($request->password, $user->password)){
            return response()->json(['message'=> 'Bad login'],401);
        }


        //Create Token
        $token = $user->createToken('mytoken')->plainTextToken;

        return response()->json([
            'user'=> $user,
            'token'=> $token
        ]);
    }
}
