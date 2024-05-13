<?php

namespace App\Http\Controllers;

use App\Helpers\UserHelper;
use App\Http\Resources\GrupAuditorList;
use App\Models\Auditor;
use App\Models\GrupAuditor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileContoller extends Controller
{
    public function myProfile()
    {
        $user = UserHelper::parseUserRole(User::with('departemen.unit')->findOrFail(auth()->user()->id), 'string');
        if (in_array('auditor', $user['role'])) {
            $auditor = Auditor::where('user_id', $user['id'])->first();
            if ($auditor && $auditor['grup_auditor_id'] != null && $auditor['keanggotaan'] != null) {
                $grups = new GrupAuditorList(GrupAuditor::with('auditor')->findOrFail($auditor['grup_auditor_id']));
                $user['grup_auditor'] = $grups;
            }
        }
        return response()->json($user, 200);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);
        $user->update([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
        ]);
        return response()->json(
            [
                'message' => 'Profil Anda berhasil diperbarui',
                'data' => [
                    'username' => $request->username,
                    'nama_lengkap' => $request->nama_lengkap,
                    'email' => $request->email,
                ],
            ],
            200,
        );
    }

    public function checkPassword(Request $request){
        $request->validate([
            'current_password' => "required"
        ]);

        $user = User::findOrFail(auth()->user()->id);
        if($user && Hash::check($request->current_password, $user->password)){
            return response()->json(true, 200);
        }else{
            return response()->json(false, 403);
        }
    }

    public function changePassword(Request $request){
        $request->validate([
            'new_password' => "required",
            'new_password_confirm' => "required"
        ]);

        // return response()->json($request->all(), 404);

        $user = User::findOrFail(auth()->user()->id);
        if($user){
            $user->update([
                'password' => Hash::make($request->new_password_confirm),
            ]);
            return response()->json(['message' => 'Password berhasil diperbarui'], 200);
        }else{
            return response()->json(['message' => 'Password gagal diperbarui'], 401);
        }
    }
}
