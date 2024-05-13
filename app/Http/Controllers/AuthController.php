<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Resources\UserLoged;
use App\Models\Iso;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    private function levelRole($role){
        if($role == "auditee"){
            return 1;
        }
        if($role == "auditor"){
            return 2;
        }
        if($role == "pdd"){
            return 3;
        }
        if($role == "management"){
            return 4;
        }
        return;
    }

    public function login(Request $request){
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'iso_id' => 'required',
        ]);
        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];
        $now = Carbon::now();
        $iso = Iso::findOrFail($request->iso_id);
        $user = User::with('departemen')->where('username', $request->username)->first();
        if($user && Hash::check($request->password, $user->password)){
            UserHelper::parseUserRole($user, "string");
            if(Auth::attempt($credentials)){
                return response()->json([
                    'user' => new UserLoged($user),
                    'meta' => [
                        'iso_id' => $request->iso_id,
                        'kode_iso' => $iso['kode'],
                        'active_role' => null,
                        'level_role' => null,
                    ],
                    'token' => [
                        'access_token' => $user->createToken($user->username)->plainTextToken,
                        'expired_at' => $now->addMinutes(60)->toDateTimeString()
                    ]
                ],200);
            }
        }
        else{
            return response()->json([
                'message' => 'Login Gagal, Username atau Password Salah'
            ],401);
        }
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logout Berhasil'
        ],200);
    }
}
