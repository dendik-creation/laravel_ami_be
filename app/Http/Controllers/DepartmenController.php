<?php

namespace App\Http\Controllers;

use App\Helpers\DepartemenHelper;
use App\Helpers\PaginateHelper;
use App\Models\Departemen;
use Illuminate\Http\Request;

class DepartmenController extends Controller
{
    public function deptList()
    {
        $departemens = Departemen::with('unit')->get();
        $data = [];
        foreach ($departemens as $item) {
            $each = [
                'label' => $item['nama_departemen'] . ' - ' . $item['unit']['nama_unit'],
                'value' => $item['id'],
            ];
            $data[] = $each;
        }
        return response()->json($data, 200);
    }

    public function index(Request $request)
    {
        if($request->has('search') && $request->search != ""){
            $dept_count = Departemen::where('nama_departemen', 'like', '%'.$request->search.'%')->count();
            $departemens = Departemen::with('unit', 'sub_departemen')->where('nama_departemen', 'like', '%'.$request->search.'%')->paginate(5);
        }else{
            $dept_count = Departemen::count();
            $departemens = Departemen::with('unit', 'sub_departemen')->paginate(5);
        }

        foreach ($departemens as $item) {
            $item['sub_departemen_count'] = count($item['sub_departemen']);
            unset($item['sub_departemen']);
        }
        $data = [
            'total_dept' => $dept_count,
            'data' => $departemens,
            'meta_paginate' => PaginateHelper::metaPaginateInfo($departemens),
        ];
        return response()->json($data, 200);
    }

    public function show($id){
        $departemen = Departemen::with('unit', 'sub_departemen')->findOrFail($id);
        $departemen['unit_select'] = [
            'label' => $departemen['unit']['kode'] . " | " . $departemen['unit']['nama_unit'],
            'value' => $departemen['unit']['id'],
        ];
        if($departemen['sub_departemen']){
            foreach ($departemen['sub_departemen'] as $item){
                $item['status'] = "then";
            }
        }
        return response()->json($departemen, 200);
    }

    public function store(Request $request){
        $request->validate([
            'kode' => 'required',
            'ekstensi' => 'required',
            'nama_departemen' => 'required',
            'unit_id' => 'required',
        ]);
        $dept = Departemen::create([
            'kode' => $request->kode,
            'ekstensi' => $request->ekstensi,
            'nama_departemen' => $request->nama_departemen,
            'unit_id' => $request->unit_id,
        ]);
        DepartemenHelper::createSubDeptPost($request->sub_departemen, $dept['id']);
        return response()->json(['message' => 'Data Departemen Berhasil Ditambahkan'], 200);
    }

    public function update($id, Request $request){
        $departemen = Departemen::findOrFail($id);
        $departemen->update([
            'kode' => $request->kode,
            'nama_departemen' => $request->nama_departemen,
            'ekstensi' => $request->ekstensi,
            'unit_id' => $request->unit_id,
        ]);
        DepartemenHelper::updateOrNewSubDept($request->sub_departemen);
        return response()->json(['message' => 'Data Departemen Berhasil DIperbarui'], 200);
    }

    public function removeSubDept(Request $request){
        DepartemenHelper::removeSubDept($request->subs_id);
    }

    public function destroy($id){
        $dept = Departemen::findOrFail($id);
        $dept->delete();
        return response()->json(['message' => 'Departemen Berhasil Dihapus'], 200);
    }
}
