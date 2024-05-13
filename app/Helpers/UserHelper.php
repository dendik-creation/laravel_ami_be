<?php

namespace App\Helpers;

use App\Models\Auditor;

class UserHelper
{
    public static function parseUserRole($data, $type)
    {
        if ($type == 'array') {
            foreach ($data as $item) {
                $item['role'] = json_decode($item['role']);
            }
        } elseif ($type == 'string') {
            $data['role'] = json_decode($data['role']);
        } elseif ($type == 'array_auditor_list') {
            foreach ($data as $item) {
                $item['user']['role'] = json_decode($item['user']['role']);
            }
        }
        return $data;
    }

    public static function updateAuditorCondition($user, $request){
        $auditor = Auditor::where('user_id', $user['id'])->first();

        // Update Auditor
        if($auditor && $auditor['status'] == 'active' && in_array('auditor', $user['role']) && in_array('auditor', $request['role'])){
            // dd('OK');
            $auditor->update([
                "keanggotaan" => $request['auditor']['keanggotaan'],
                "grup_auditor_id" => $request['auditor']['grup_auditor_id'],
            ]);
        // Disabled Auditor
        }else if($auditor && $auditor['status'] == 'active' && in_array('auditor', $user['role']) && !in_array('auditor', $request['role'])){
            $auditor->update([
                "status" => "disabled",
                "grup_auditor_id" => null,
                "keanggotaan" => null,
            ]);
            // Activated Auditor
        }else if($auditor && $auditor['status'] == 'disabled' && !in_array('auditor', $user['role']) && in_array('auditor', $request['role'])){
            $auditor->update([
                "keanggotaan" => $request['auditor']['keanggotaan'],
                "grup_auditor_id" => $request['auditor']['grup_auditor_id'],
                "status" => "active",
            ]);
        // New Auditor
        }else if(!$auditor && !in_array('auditor', $user['role']) && in_array('auditor', $request['role'])){
            Auditor::create([
                "user_id" => $user['id'],
                "keanggotaan" => $request['auditor']['keanggotaan'],
                "grup_auditor_id" => $request['auditor']['grup_auditor_id'],
            ]);
        }else{
            return;
        }
        return;
    }
}
