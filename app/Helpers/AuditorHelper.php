<?php

namespace App\Helpers;

use App\Http\Resources\GrupAuditorList;
use App\Models\Auditee;
use App\Models\HeaderAudit;
use App\Models\Auditor;
use App\Models\Clausul;
use App\Models\Departemen;
use App\Models\DetailAudit;
use App\Models\GrupAuditor;
use App\Models\Iso;
use App\Models\JudulClausul;
use App\Models\SubClausul;
use App\Models\SubDepartemen;
use Illuminate\Support\Facades\Auth;

class AuditorHelper{

    public static function countKategori($data){
        $result = [
            'mayor' => 0,
            'minor' => 0,
            'observasi' => 0,
            'total' => 0,
        ];
        if($data){
            foreach ($data as $item){
                foreach ($item->detail_audit as $sub_dept){
                    $result['total']++;
                    if($sub_dept['kategori'] == 'mayor'){
                        $result['mayor']++;
                    }
                    elseif($sub_dept['kategori'] == 'minor'){
                        $result['minor']++;
                    }
                    elseif($sub_dept['kategori'] == 'observasi'){
                        $result['observasi']++;
                    }
                }
            }
            return $result;
        }else{
            return NULL;
        }
    }

    public static function myAuditStats(){
        $auditor = Auditor::where('user_id', Auth::user()->id)->first();
        if($auditor){
            $the_audits = HeaderAudit::with('detail_audit')->where('grup_auditor_id', $auditor->grup_auditor_id)->get();
            if($the_audits) return $the_audits;
        }else{
            return NULL;
        }
    }

    public static function createHeaderAudit($data){
        $data['static_data'] = json_encode([
            'grup_auditor' => new GrupAuditorList(GrupAuditor::with('auditor')->findOrFail($data['grup_auditor_id'])),
            'auditee' => Auditee::with('user.departemen.unit')->findOrFail($data['auditee_id']),
            'iso' => Iso::findOrFail($data['iso_id']),
            'departemen' => Departemen::with('unit')->findOrFail($data['departemen_id']),
        ]);
        $header = HeaderAudit::create($data);
        return $header;
    }

    public static function createDetailAudit($data, $header_id){
        foreach ($data as $item){
            $item['header_audit_id'] = $header_id;
            $item['static_data'] = [
                'judul_clausul' => JudulClausul::findOrFail($item['judul_clausul_id']),
            ];
            if($item['clausul_id'] != null){
                $item['static_data']['clausul'] = Clausul::findOrFail($item['clausul_id']);
            }
            if($item['sub_clausul_id'] != null){
                $item['static_data']['sub_clausul'] = SubClausul::findOrFail($item['sub_clausul_id']);
            }
            if($item['sub_departemen_id'] != null){
                $item['static_data']['sub_departemen'] = SubDepartemen::with('departemen.unit')->findOrFail($item['sub_departemen_id']);
            }
            $item['static_data'] = json_encode($item['static_data']);
            $detail = DetailAudit::create($item);
            // $detail = DetailAudit::create([
            //     "header_audit_id" => $header_id,
            //     "judul_clausul_id" => $item['judul_clausul_id'],
            //     "clausul_id" => $item['clausul_id'],
            //     "sub_clausul_id" => $item['sub_clausul_id'],
            //     "sub_departemen_id" => $item['sub_departemen_id'],
            //     "tanggal_audit" => $item['tanggal_audit'],
            //     "tanggal_realisasi" => $item['tanggal_realisasi'],
            //     "tanggal_target" => $item['tanggal_target'],
            //     "kategori" => $item['kategori'],
            //     "temuan" => $item['temuan'],
            //     "jenis_temuan" => $item['jenis_temuan'],
            //     "status" => $item['status'],
            //     "periode" => $item['periode'],
            // ]);
        }
        return true;
    }

    public static function newAuditGetDataConvert($auditee, $judul_clausul, $clausul, $sub_clausul){
        $response = [
            "auditee" => [],
            "judul_clausul" => [],
            "clausul" => [],
            "sub_clausul" => [],
        ];
        if($auditee->count() > 0){
            foreach ($auditee as $item){
                $data = [
                    "label" => $item['user']['nama_lengkap'],
                    "value" => $item['id'],
                    "departemen_id" => $item['user']['departemen_id'],
                    "departemen_kode" => $item['user']['departemen']['kode'],
                    "unit_kode" => $item['user']['departemen']['unit']['kode'],
                ];
                $response['auditee'][] = $data;
            }
        }
        if($judul_clausul->count() > 0){
            foreach ($judul_clausul as $item){
                $data = [
                    "label" => $item['kode'] ." - ". $item['judul_clausul'],
                    "value" => $item['id'],
                ];
                $response['judul_clausul'][] = $data;
            }
        }

        if($clausul->count() > 0){
            foreach ($clausul as $item){
                $data = [
                    "label" => $item['kode'] ." - ". $item['nama_clausul'],
                    "value" => $item['id'],
                    "judul_clausul_id" => $item['judul_clausul_id'],
                ];
                $response['clausul'][] = $data;
            }
        }
        if($sub_clausul->count() > 0){
            foreach ($sub_clausul as $item){
                $data = [
                    "label" => $item['kode'] ." - ". $item['nama_sub_clausul'],
                    "value" => $item['id'],
                    "judul_clausul_id" => $item['judul_clausul_id'],
                    "clausul_id" => $item['clausul_id'],
                ];
                $response['sub_clausul'][] = $data;
            }
        }

        return $response;
    }

    public static function leadFirstThenMemberGrupAuditor($data){
        $array = $data->toArray();
        usort($array, function ($a, $b) {
            if ($a['keanggotaan'] === 'ketua') {
                return -1;
            } elseif ($b['keanggotaan'] === 'ketua') {
                return 1;
            } else {
                return 0;
            }
        });
        return $array;
    }

    public static function handleEditGetAuditHeader($header){
        return [
            "no_plpp" => $header['no_plpp'],
            // "temuan_audit" => [
            //     'label' => ucfirst($header['temuan_audit']),
            //     'value' => $header['temuan_audit'],
            // ],
            "temuan_audit" => $header['temuan_audit'],
            "auditee_id" => $header['auditee_id'],
            "iso_id" => $header['iso_id'],
            "grup_auditor_id" => $header['grup_auditor_id'],
            // "auditee_id" => [
            //     "label" => $header['auditee']['user']['nama_lengkap'],
            //     "value" => $header['auditee_id'],
            //     "departemen_id" => $header['departemen_id'],
            //     "departemen_kode" => $header['departemen']['kode'],
            //     "unit_kode" => $header['departemen']['unit']['kode'],
            // ]
        ];
    }

    public static function handleEditGetAuditDetail($details){
        $returned = [];
        foreach ($details as $item) {
            $item['judul_clausul_id'] = [
                'label' => $item['judul_clausul']['kode'] . " - ". $item['judul_clausul']['judul_clausul'],
                'value' => $item['judul_clausul_id'],
            ];
            $item['clausul_id'] = [
                'label' => $item['clausul']['kode'] . " - ". $item['clausul']['nama_clausul'],
                'value' => $item['clausul_id'],
            ];
            $item['sub_clausul_id'] = [
                'label' => $item['sub_clausul']['kode'] . " - ". $item['sub_clausul']['nama_sub_clausul'],
                'value' => $item['sub_clausul_id'],
            ];
            if($item['sub_departemen_id'] != null){
                $item['sub_departemen_id'] = [
                    'label' => $item['sub_departemen']['nama_sub_departemen'],
                    'value' => $item['sub_departemen_id'],
                ];
            }
            $returned[] = $item;
        }
        return $returned;
    }
}
