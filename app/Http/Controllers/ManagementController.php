<?php

namespace App\Http\Controllers;

use App\Helpers\AuditorHelper;
use App\Helpers\ManagementHelper;
use App\Helpers\PaginateHelper;
use App\Http\Resources\AuditClosedNotEnd;
use App\Http\Resources\MyAuditList;
use App\Mail\AuditBroadcast;
use App\Mail\AuditNotify;
use App\Models\Auditee;
use App\Models\DetailAudit;
use App\Models\HeaderAudit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ManagementController extends Controller
{
    public function auditCloses(Request $request){
        $now = Carbon::now();
        $audits_not_responded_count = HeaderAudit::where('is_responded', 0)->whereDate('end_at', "<", $now)->count();
        $audits = MyAuditList::collection(HeaderAudit::with('departemen.unit', 'iso', 'grup_auditor.auditor', 'detail_audit', 'auditee.user.departemen.unit')->where('is_responded', 0)->whereDate('end_at', "<", $now)->paginate(5));
        $data = [
            "audit_not_responded_count" => $audits_not_responded_count,
            "audits" => $audits,
            "meta" => PaginateHelper::metaPaginateInfo($audits)
        ];
        return response()->json($data, 200);
    }

    public function continueAudit($header_id, Request $request){
        $now = Carbon::now();
        $target_days = $now->addDays($request->banyak_hari)->toDateString();
        $header = HeaderAudit::with('detail_audit')->findOrFail($header_id);
        $header->update([
            'is_responded' => 0,
            "end_at" => $target_days,
        ]);
        // $alamak = [];
        if($header['detail_audit']){
            foreach($header['detail_audit'] as $item){
                $detail = DetailAudit::findOrFail($item['id']);
                $detail->update([
                    'status' => 'open',
                    'tanggal_target' => $target_days,
                    'tanggal_realisasi' => $target_days,
                ]);
                // $alamak[] = $target_days;
            }
        }
        // return response()->json($alamak, 404);
        return response()->json(['message' => 'Proses audit berhasil diperpanjang'], 200);
    }

    public function reminderAuditee(Request $request){
        $data = null;
        $auditee = null;
        if($request->has('header_audit_id') && !$request->has('auditee_id')){
            $data = HeaderAudit::with('grup_auditor.auditor', 'iso', 'auditee.user.departemen.unit', 'departemen', 'detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->findOrFail($request->header_audit_id);
            $auditee = Auditee::with('user.departemen.unit')->findOrFail($data['auditee_id']);
        }else{
            $data = HeaderAudit::with('grup_auditor.auditor', 'iso', 'auditee.user.departemen.unit', 'departemen', 'detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->where('auditee_id', $request->auditee_id)->where('is_responded', 0)->get();
            $auditee = Auditee::with('user.departemen.unit')->findOrFail($request->auditee_id);
        }
        Mail::to($auditee['user']['email'])->send(new AuditNotify($data, $auditee));
        return response()->json(["message" => "Email pengingat berhasil dikirimkan ke auditee"], 200);
    }

    public function broadcastAuditee(){
        $audits = HeaderAudit::with('grup_auditor.auditor', 'iso', 'auditee.user.departemen.unit', 'departemen', 'detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->where('is_responded', 0)->get()->groupBy('auditee_id');
        if($audits){
            foreach ($audits as $index => $item){
                Mail::to($audits[$index][0]['auditee']['user']['email'])->send(new AuditBroadcast($item));
            }
            return response()->json(["message" => "OK"], 200);
        }
    }
}
