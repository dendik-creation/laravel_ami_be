<?php

namespace App\Helpers;

use App\Models\Clausul;
use App\Models\JudulClausul;
use App\Models\SubClausul;

class ClausulHelper {

    public static function createClausul($clausuls, $judul_clausul_id){
        foreach($clausuls as $item){
            $clausul = Clausul::create([
                'kode' => $item['kode_clausul'],
                'nama_clausul' => $item['nama_clausul'],
                'judul_clausul_id' => $judul_clausul_id
            ]);
            ClausulHelper::createSubClausul($item['sub_clausul'], $judul_clausul_id, $clausul['id']);
        }
    }
    public static function createSubClausul($sub_clausuls, $judul_clausul_id, $clausul_id){
        foreach ($sub_clausuls as $item){
            SubClausul::create([
                "kode" => $item['kode_sub_clausul'],
                "judul_clausul_id" => $judul_clausul_id,
                "clausul_id" => $clausul_id,
                "nama_sub_clausul" => $item['nama_sub_clausul'],
            ]);
        }
    }

    public static function updateJudulClausul($id, $requested){
        $judul_clausul = JudulClausul::findOrFail($id);
        $judul_clausul->update([
            "kode" => $requested['kode'],
            "judul_clausul" => $requested['judul_clausul'],
            "iso_id" => $requested['iso_id']['value'],
        ]);
        if($requested['clausul']){
            ClausulHelper::updateClausul($requested['clausul']);
        }
    }

    public static function updateClausul($clausuls){
        if($clausuls){
            foreach ($clausuls as $item){
                $id_tosend = $item['id'] ?? null;
                if($item['status'] == "then"){
                    $clausul = Clausul::findOrFail($item['id']);
                    $clausul->update([
                        "kode" => $item['kode_clausul'],
                        "nama_clausul" => $item['nama_clausul'],
                    ]);
                }else if($item['status'] == "new"){
                    $clausul_created = Clausul::create([
                        "judul_clausul_id" => $item['judul_clausul_id'],
                        "kode" => $item['kode_clausul'],
                        "nama_clausul" => $item['nama_clausul'],
                    ]);
                    $id_tosend = $clausul_created['id'];
                }
                if($item['sub_clausul']){
                    ClausulHelper::updateSubClausul($item['sub_clausul'], $id_tosend);
                }
            }
        }
    }

    public static function updateSubClausul($sub_clausuls, $clausul_id){
        if($sub_clausuls){
            foreach ($sub_clausuls as $item){
                if($item['status'] == "then"){
                    $sub_clausul = SubClausul::findOrFail($item['id']);
                    $sub_clausul->update([
                        "clausul_id" => $clausul_id,
                        "kode" => $item['kode_sub_clausul'],
                        "nama_sub_clausul" => $item['nama_sub_clausul'],
                    ]);
                }else if($item['status'] == "new"){
                    SubClausul::create([
                        "judul_clausul_id" => $item['judul_clausul_id'],
                        "clausul_id" => $clausul_id,
                        "nama_sub_clausul" => $item['nama_sub_clausul'],
                        "kode" => $item['kode_sub_clausul'],
                    ]);
                }
            }
        }
    }

    public static function deleteJudulClausul($id){
        $judul_clausul = JudulClausul::findOrFail($id);
        $judul_clausul->delete();
    }

    public static function deleteClausul($clausuls = null){
        if($clausuls){
            foreach ($clausuls as $item){
                $clausul = Clausul::findOrFail($item['id']);
                $clausul->delete();
            }
        }
    }

    public static function deleteSubClausul($sub_clausuls = null){
        if($sub_clausuls){
            foreach ($sub_clausuls as $item){
                $sub_clausul = SubClausul::findOrFail($item['id']);
                $sub_clausul->delete();
            }
        }
    }
}
