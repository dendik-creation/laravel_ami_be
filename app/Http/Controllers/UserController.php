<?php

namespace App\Http\Controllers;

use App\Helpers\PaginateHelper;
use App\Helpers\UserHelper;
use App\Models\Auditee;
use App\Models\Auditor;
use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->has('search') && $request->search != ""){
            $user_count = User::where('nama_lengkap', 'like', '%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%')->count();
            $users = UserHelper::parseUserRole(User::with('departemen.unit')->where('nama_lengkap', 'like', '%'.$request->search.'%')->orWhere('email', 'like', '%'.$request->search.'%')->paginate(10), "array");
        }else{
            $user_count = User::count();
            $users = UserHelper::parseUserRole(User::with('departemen.unit')->paginate(10), "array");
        }
        return response()->json([
            "total_user" => $user_count,
            "data" => $users,
            "meta" => PaginateHelper::metaPaginateInfo($users)
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function resetPass($id){
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('12345')]);
        return response()->json(['message' => 'Password User Berhasil Direset'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // return $request->all();
        $request->validate([
            "username" => 'required|unique:users,username',
            "nama_lengkap" => 'required',
            "email" => 'required|email|unique:users,email',
            "password" => 'required',
            "role" => 'required',
            "departemen_id" => "required",
        ], [
            'username.unique' => 'Username telah dipakai oleh user lain',
            'email.unique' => 'Email telah dipakai oleh user lain',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => json_encode($request->role),
            'periode_active_role' => $request->periode_active_role,
            'departemen_id' => $request->departemen_id,
        ]);

        if(in_array('auditee', $request->role)){
            $auditee = Auditee::create([
                'user_id' => $user['id'],
            ]);
        }

        if(in_array("auditor", $request->role)){
            $auditor = Auditor::create([
                "user_id" => $user['id'],
                "keanggotaan" => null,
                "grup_auditor_id" => null,
            ]);
        }

        return response()->json(["message" => "User baru berhasil ditambahkan"], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            $user = UserHelper::parseUserRole(User::with('departemen.unit')->findOrFail($id), "string");
            $user['departemen_id'] = [
                "label" => $user['departemen']['nama_departemen'] . " - " . $user['departemen']['unit']['nama_unit'],
                "value" => $user['departemen_id'],
            ];
            $auditor = Auditor::with('grup_auditor')->where('user_id', $user['id'])->whereNot('grup_auditor_id', null)->whereNot('keanggotaan', null)->first();
            if($auditor != null){
                $user['auditor'] = $auditor;
                $user['auditor']['grup_auditor']['label'] = $auditor['grup_auditor']['nama_grup'];
                $user['auditor']['grup_auditor']['value'] = $auditor['grup_auditor']['id'];
                $user['auditor']['keanggotaan'] = [
                    "label" => ucfirst($user['auditor']['keanggotaan']),
                    "value" => $user['auditor']['keanggotaan'],
                ];
                unset($user['auditor']['grup_auditor']['id']);
                unset($user['auditor']['grup_auditor']['nama_grup']);
            }
            return response()->json($user, 200);

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $request->validate([
        //     "username" => 'unique:users,username',
        //     "email" => 'email|unique:users,email',
        // ], [
        //     'username.unique' => 'Username telah dipakai oleh user lain',
        //     'email.unique' => 'Email telah dipakai oleh user lain',
        // ]);

        $user = UserHelper::parseUserRole(User::findOrFail($id), "string");
        if($user){
            // UserHelper::updateAuditorCondition($user, $request->all());
            $user->update([
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'role' => json_encode($request->role),
                'departemen_id' => $request->departemen_id,
                'periode_active_role' => $request->periode_active_role,
            ]);
            return response()->json(['message' => "User berhasil diperbarui"], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        if($user){
            $user->delete();
            return response()->json(['message' => 'User berhasil dihapus beserta historinya'], 200);
        }else{
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }
    }
}
