<?php

namespace App\Helpers;

use App\Http\Resources\AuditClosedNotEnd;
use App\Mail\AuditNotify;
use App\Models\DetailAudit;
use App\Models\HeaderAudit;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class AuditeeHelper{

    public static function onlyMostFiveAuditMe($data){
        // return $data;
        $result = [];
        if ($data->count() > 0){
            foreach ($data as $item){
                $indexData = [];
                $indexData['total_audit'] = $item->count();
                foreach ($item as $indexes){
                    $indexData['nama_grup'] = $indexes['grup_auditor']['nama_grup'];
                    $indexData["ketua_auditor"] = $indexes['grup_auditor']['auditor']->whereStrict('keanggotaan', 'ketua')->first();
                }
                array_push($result, $indexData);
            }
             $sort_by_audit_count = collect($result)->sortByDesc('total_audit')->values()->all();
             return $sort_by_audit_count;
        }
    }

    public static function responAuditAction($data){
        $now = Carbon::now();
        $detail_audit = DetailAudit::where('id', $data['id'])->first();
        if($detail_audit){
            if($detail_audit['temuan'] != null){
                if($detail_audit['tanggal_realisasi'] != null){
                    if($now->lte($detail_audit['tanggal_realisasi'])){
                        $detail_audit->update([
                            "analisa" => $data['analisa'],
                            "tindakan" => $data['tindakan'],
                            "status" => "close",
                        ]);
                        return [
                            "message" => "Audit Berhasil Diselesaikan",
                            "status" => 200,
                        ];
                    }else{
                        return [
                            "message" => "Audit Anda melebihi dari tanggal target. Hubungi Manajemen",
                            "status" => 403,
                        ];
                    }
                }else if($now->lte($detail_audit['tanggal_target'])){
                    $detail_audit->update([
                        "analisa" => $data['analisa'],
                        "tindakan" => $data['tindakan'],
                        "status" => "close",
                    ]);
                    return [
                        "message" => "Audit Berhasil Diselesaikan",
                        "status" => 200,
                    ];
                }else{
                    return [
                        "message" => "Audit Anda melebihi dari tanggal target. Hubungi Manajemen",
                        "status" => 403,
                    ];
                }
            }else{
                return [
                    "message" => "Audit ini tidak memiliki temuan",
                    "status" => 403,
                ];
            }
        }
    }

    public static function getAuditClosedNotEnd(){
        $audits = ManagementHelper::onlyHeaderNotNull(AuditClosedNotEnd::collection(HeaderAudit::with('departemen.unit', 'iso', 'grup_auditor', 'detail_audit', 'auditee.user:id,username,nama_lengkap,email')->get()));

        return $audits;
    }

    public static function notifyEmailAuditee($audits){
        if($audits){
            foreach ($audits as $item){
                Mail::to($item['auditee']['user']['email'])->send(new AuditNotify($item, $item['auditee']));
            }
        }
    }

}
