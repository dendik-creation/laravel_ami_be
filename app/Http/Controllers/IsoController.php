<?php

namespace App\Http\Controllers;

use App\Helpers\PaginateHelper;
use App\Models\Iso;
use Illuminate\Http\Request;

class IsoController extends Controller
{
    public function isoListSelect(){
        $data = Iso::all();
        $returned = [];
        foreach ($data as $item){
            $each = [
                "label" => $item['kode'],
                "value" => $item['id'],
            ];
            $returned[] = $each;
        }
        return response()->json($returned, 200);
    }

    public function index(Request $request){
        if($request->has('search') && $request->search != ""){
            $iso_count = Iso::where('kode', 'like', '%'.$request->search.'%')->count();
            $iso = Iso::with('header_audit')->where('kode', 'like', '%'.$request->search.'%')->paginate(5);
        }else{
            $iso_count = Iso::count();
            $iso = Iso::with('header_audit')->paginate(5);
        }

        foreach ($iso as $item){
            $item['many_use'] = count($item['header_audit']);
            unset($item['header_audit']);
        }
        $data = [
            "iso_count" => $iso_count,
            "iso" => $iso,
            "meta_paginate" => PaginateHelper::metaPaginateInfo($iso),
        ];
        return response()->json($data, 200);
    }

    public function show($id){
        $iso = Iso::findOrFail($id);
        return response()->json($iso, 200);
    }

    public function store(Request $request){
        $request->validate([
            'kode' => "required",
        ]);
        $iso = Iso::create($request->all());
        return response()->json(['message' => 'Standar ISO baru berhasil ditambahkan'], 200);
    }

    public function update($id, Request $request){
        $iso = Iso::findOrFail($id);
        $iso->update([
            'kode' => $request->kode,
        ]);
        return response()->json(['message' => 'Kode ISO berhasil diperbarui'], 200);
    }

    public function destroy($id){
        $iso = Iso::findOrFail($id);
        $iso->delete();
        return response()->json(['message' => 'Standar ISO berhasil dihapus'], 200);
    }
}
