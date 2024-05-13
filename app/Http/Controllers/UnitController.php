<?php

namespace App\Http\Controllers;

use App\Helpers\PaginateHelper;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function unitSelect(){
        $units = Unit::all();
        $data = [];
        if($units){
            foreach ($units as $item){
                $each = [
                    "label" => $item['kode'] ." | ".$item['nama_unit'],
                    "value" => $item['id'],
                ];
                $data[] = $each;
            }
            return response()->json($data, 200);
        }
    }

    public function index(Request $request){
        if($request->has('search') && $request->search != ""){
            $unit_count = Unit::where('nama_unit', 'like', '%'.$request->search.'%')->count();
            $unit = Unit::with('departemen')->where('nama_unit', 'like', '%'.$request->search.'%')->paginate(5);
        }else{
            $unit_count = Unit::count();
            $unit = Unit::with('departemen')->paginate(5);
        }

        foreach ($unit as $item){
            $item['departemen_count'] = count($item['departemen']);
            unset($item['departemen']);
        }
        $data = [
            "unit_count" => $unit_count,
            "unit" => $unit,
            "meta_paginate" => PaginateHelper::metaPaginateInfo($unit),
        ];
        return response()->json($data, 200);
    }

    public function show($id){
        $unit = Unit::findOrFail($id);
        return response()->json($unit, 200);
    }

    public function store(Request $request){
        $request->validate([
            'kode' => "required",
            'nama_unit' => "required",
        ]);
        $unit = Unit::create($request->all());
        return response()->json(['message' => 'Unit baru berhasil ditambahkan'], 200);
    }

    public function update($id, Request $request){
        $unit = Unit::findOrFail($id);
        $unit->update([
            'kode' => $request->kode,
            'nama_unit' => $request->nama_unit,
        ]);
        return response()->json(['message' => 'Unit berhasil diperbarui'], 200);
    }

    public function destroy($id){
        $unit = Unit::findOrFail($id);
        $unit->delete();
        return response()->json(['message' => 'Unit berhasil dihapus'], 200);
    }
}
