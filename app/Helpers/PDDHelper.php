<?php

namespace App\Helpers;

class PDDHelper {
    public static function onlyMostFiveGrupAudit($data){
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

    public static function countUserByRole($data){
        $user_by_role = [
            "management" => 0,
            "pdd" => 0,
            "auditor" => 0,
            "auditee" => 0,
        ];
        foreach ($data as $user) {
            foreach ($user['role'] as $userRole) {
                if (array_key_exists($userRole, $user_by_role)) {
                    $user_by_role[$userRole]++;
                }
            }
        }
        return $user_by_role;
    }

    public static function sortDeptByAuditCount($data){
        usort($data, function ($a, $b) {
            return $b['audit_count'] - $a['audit_count'];
        });
    }
}
