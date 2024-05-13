<?php

namespace App\Http\Controllers;

use App\Helpers\ClausulHelper;
use App\Helpers\PaginateHelper;
use App\Models\JudulClausul;
use Illuminate\Http\Request;

class ClausulController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->has('search') && $request->search != ""){
            $judul_clausul_count = JudulClausul::where('judul_clausul', 'like', '%'.$request->search.'%')->count();
            $judul_clausul = JudulClausul::with('iso')->where('judul_clausul', 'like', '%'.$request->search.'%')->paginate(5);
        }else{
            $judul_clausul_count = JudulClausul::count();
            $judul_clausul = JudulClausul::with('iso')->paginate(5);
        }

        $data = [
            'total_clausul' => $judul_clausul_count,
            'data' => $judul_clausul,
            'meta_paginate' => PaginateHelper::metaPaginateInfo($judul_clausul),
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
            'kode' => 'required',
            'judul_clausul' => 'required',
            'iso_id' => 'required',
            'clausul' => 'required',
        ]);

        $judul_clausul = JudulClausul::create([
            'kode' => $request->kode,
            'judul_clausul' => $request->judul_clausul,
            'iso_id' => $request->iso_id,
        ]);

        ClausulHelper::createClausul($request->clausul, $judul_clausul['id']);

        return response()->json(['message' => 'Clausul dan Subnya berhasil dibuat'], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $judul_clausul = JudulClausul::with('clausul.sub_clausul', 'iso')->where('id', $id)->first();
        // Ubah Key Kode menjadi sesuai susunannya
        foreach ($judul_clausul['clausul'] as $item_clausul){
            $item_clausul['kode_clausul'] = $item_clausul['kode'];
            unset($item_clausul['kode']);
            foreach($item_clausul['sub_clausul'] as $item_sub_clausul){
                $item_sub_clausul['kode_sub_clausul'] = $item_sub_clausul['kode'];
                unset($item_sub_clausul['kode']);
            }
        }
        if ($judul_clausul) {
            return response()->json($judul_clausul, 200);
        } else {
            return response()->json(['message' => 'Clausul tidak ditemukan'], 401);
        }
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
    public function update(Request $request, $id)
    {
        ClausulHelper::updateJudulClausul($id, $request->all());
        return response()->json(['message' => 'Data clausul berhasil diperbarui'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCustom(Request $request)
    {
        ClausulHelper::deleteClausul($request->clausul);
        ClausulHelper::deleteSubClausul($request->sub_clausul);
        return response()->json(['message' => 'Data clausul yang terpilih berhasil dihapus'], 200);
    }

    public function destroy(string $id)
    {
        ClausulHelper::deleteJudulClausul($id);
        return response()->json(['message' => 'Clausul beserta sub-nya berhasil dihapus'], 200);
    }
}
