<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    function login(Request $request){
        $user= User::where('email', $request->email)->first();
        
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['Bu kimlik bilgileri kayıtlarımızla eşleşmiyor.']
                ], 404);
            }

            $token = $user->createToken($request->email)->plainTextToken;
            $user->remember_token = $token;        

            $response = [
                'user' => $user,
                'token' => $token
            ];

        return response($response, 201);
    }

    function create(Request $request){

        $lastuser = User::where('email', $request->email)->first();

        if($lastuser == null){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message'=> 'Üye başarıyla oluşturuldu']);
        }else{
            return response()->json(['message'=> 'Bu üye zaten tanımlı.'], 409);
        }
        
    } 
}
