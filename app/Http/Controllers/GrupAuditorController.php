<?php

namespace App\Http\Controllers;

use App\Helpers\PaginateHelper;
use App\Http\Resources\GrupAuditorList;
use App\Models\Auditor;
use App\Models\GrupAuditor;
use Illuminate\Http\Request;

class GrupAuditorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->has('search') && $request->search != ""){
            $grup_auditor_count = GrupAuditor::where('nama_grup', 'like', '%'.$request->search.'%')->count();
            $auditors = GrupAuditorList::collection(GrupAuditor::with('auditor')->where('nama_grup', 'like', '%'.$request->search.'%')->paginate(10));
        }else{
            $grup_auditor_count = GrupAuditor::count();
            $auditors = GrupAuditorList::collection(GrupAuditor::with('auditor')->paginate(10));
        }

        $data = [
            "total_grup" => $grup_auditor_count,
            "data" => $auditors,
            "meta_paginate" => PaginateHelper::metaPaginateInfo($auditors),
        ];
        return response()->json($data, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_grup' => 'required',
            'ketua' => 'required',
            'auditors_id' => 'required',
        ]);
        // grup
        $grup = GrupAuditor::create([
            'nama_grup' => $request->nama_grup,
        ]);
        // ketua
        $ketua = Auditor::where('user_id', $request->ketua['user_id'])->first();
        if($ketua){
            $ketua->update([
                'grup_auditor_id' => $grup['id'],
                'keanggotaan' => 'ketua',
            ]);
        }else{
            Auditor::create([
                "user_id" => $request->ketua['user_id'],
                'grup_auditor_id' => $grup['id'],
                'keanggotaan' => 'ketua',
            ]);
        }
        // anggota_list
        if ($request->auditors_id) {
            foreach ($request->auditors_id as $id) {
                $auditor = Auditor::findOrFail($id);
                $auditor->update([
                    'grup_auditor_id' => $grup['id'],
                    'keanggotaan' => 'anggota',
                ]);
            }
        }

        return response()->json(['message' => 'Grup auditor berhasil dibuat'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $grups = new GrupAuditorList(GrupAuditor::with('auditor')->findOrFail($id));
        return response()->json($grups, 200);
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
        $grup = GrupAuditor::findOrFail($id);
        // $toArrayGrup = $grup->toArray($request);

        // Apdted Grup
        $grup->update([
            "nama_grup" => $request->nama_grup,
        ]);

        // Disable then ketua
        // if($toArrayGrup['auditor_list'] && $toArrayGrup['auditor_list'][0]){
        //     $then_ketua = Auditor::findOrFail($toArrayGrup['auditor_list'][0]['id']);
        //     $then_ketua->update([
        //         "grup_auditor_id" => null,
        //         "keanggotaan" => null,
        //     ]);
        // }

        // Update Ketua
        $new_ketua = Auditor::where('user_id', $request->ketua_auditor['user_id_then'])->first();
        if($new_ketua){
            $new_ketua->update([
                "user_id" => $request->ketua_auditor['user_id_new'],
                "grup_auditor_id" => $id,
                "keanggotaan" => 'ketua',
            ]);
        }

        return response()->json(['message' => 'Grup auditor berhasil diperbarui'], 200);
    }

    public function updateCreate(Request $request, string $id)
    {
        // anggota_list
        if ($request->auditor_user_id) {
            foreach ($request->auditor_user_id as $item) {
                $auditor = Auditor::where('user_id', $item)->first();
                if($auditor){
                    $auditor->update([
                        'grup_auditor_id' => $id,
                        'keanggotaan' => 'anggota',
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Anggota baru ditambahkan ke grup auditor'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $grup = GrupAuditor::findOrFail($id);
        $auditors = Auditor::where('grup_auditor_id', $id)->get();
        if($auditors){
            foreach ($auditors as $item){
                $item->update([
                    'grup_auditor_id' => null,
                    'keanggotaan' => null,
                ]);
            }
        }
        $grup->delete();
        return response()->json(['message' => 'Grup auditor berhasil dihapus'], 200);
    }

    public function destroyMember(Request $request)
    {
        // set null auditor
        if ($request->auditor_user_id) {
            foreach ($request->auditor_user_id as $item) {
                $auditor = Auditor::where('user_id', $item)->first();
                if($auditor){
                    $auditor->update([
                        'grup_auditor_id' => null,
                        'keanggotaan' => null,
                    ]);
                }
            }
        }
        return response()->json(['message' => 'Anggota terpilih berhasil dilepaskan'], 200);
    }

    public function grupAuditorList(){
        $grups = GrupAuditor::with('auditor')->get();
        $data = [];
        foreach ($grups as $item){
            $each = [
                "label" => $item['nama_grup'],
                "value" => $item['id'],
                "has_ketua" => false,
            ];
            foreach ($item['auditor'] as $auditor){
                if($auditor['keanggotaan'] == "ketua"){
                    $each['has_ketua'] = true;
                    break;
                }
            }
            $data[] = $each;
        }
        return response()->json($data, 200);
    }
}
