<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AccessToken;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
class Authcontroller extends Controller
{
    public function login(Request $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([ 'message' => 'Invalid credential' ]);
            }

            $check = Hash::check($request->password, $user->password);
            if (!$check) {
                return response()->json([ 'message' => 'Invalid credential' ]);
            }
            
            $accessToken = AccessToken::updateOrCreate(
                [ 'user_id' => $user->id ],
                [ 'access_token' => Str::random(255) ]
            );
            return response()->json([ 'access_token' =>  $accessToken->access_token ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout(Request $request)
    {
        try {
            $accessToken = AccessToken::where('access_token', $request->access_token)->first();
            if ($accessToken) {
                $accessToken->delete();
                return response()->json([ 'success' => true ]);
            }
            
            return response()->json([ 'success' => false ]); 
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
