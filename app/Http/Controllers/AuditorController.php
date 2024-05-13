<?php

namespace App\Http\Controllers;

use App\Helpers\AuditorHelper;
use App\Helpers\PaginateHelper;
use App\Helpers\UserHelper;
use App\Http\Resources\DepartemenList;
use App\Http\Resources\GrupAuditorList;
use App\Http\Resources\MyAuditList;
use App\Models\Auditee;
use App\Models\Auditor;
use App\Models\Clausul;
use App\Models\Departemen;
use App\Models\DetailAudit;
use App\Models\GrupAuditor;
use App\Models\HeaderAudit;
use App\Models\JudulClausul;
use App\Models\SubClausul;
use App\Models\SubDepartemen;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AuditorController extends Controller
{

    public function dashboard(){
        $my_audits = AuditorHelper::countKategori(AuditorHelper::myAuditStats());
        $all_audits = AuditorHelper::countKategori(HeaderAudit::with('detail_audit')->get());
        $auditor_count = Auditor::count();
        $dept_audited_not_responded = HeaderAudit::with('auditee.user', 'departemen.unit', 'grup_auditor')->where('is_responded', 0)->latest()->take(5)->get();
        return response()->json([
            'my_audit' => $my_audits,
            'all_audit' => $all_audits,
            'total_auditor' => $auditor_count,
            'departemen_list' => $dept_audited_not_responded,
        ]);
    }

    public function newAudit(Request $request){
        $header = AuditorHelper::createHeaderAudit($request->header_audit);
        if($request->detail_audit != null){
            $detail = AuditorHelper::createDetailAudit($request->detail_audit, $header['id']);
        }else{
            $headerUpdate = HeaderAudit::findOrFail($header['id'])->update(['is_responded' => 1]);
        }

        if($header != null){
            return response()->json([
                "message" => "Audit Baru Berhasil Dibuat"
            ], 200);
        }
    }

    public function newAuditData(){
        $auditee = Auditee::with('user.departemen.unit')->whereNot('user_id', auth()->user()->id)->get();
        $judul_clausul = JudulClausul::all();
        $clausul = Clausul::all();
        $sub_clausul = SubClausul::all();

        $data = AuditorHelper::newAuditGetDataConvert($auditee, $judul_clausul, $clausul, $sub_clausul);
        return response()->json($data, 200);
    }

    public function editAuditData($id){
        $header = HeaderAudit::with('auditee.user.departemen', 'iso', 'departemen.unit', 'grup_auditor')->findOrFail($id);
        $details = DetailAudit::with('judul_clausul', 'clausul', 'sub_clausul', 'sub_departemen')->where('header_audit_id', $id)->get();
        $results = [
            'header_audit' => AuditorHelper::handleEditGetAuditHeader($header),
            'detail_audit' => AuditorHelper::handleEditGetAuditDetail($details),
        ];
        return response()->json($results, 200);
    }

    public function pihakTerlibat ($auditee_id){
        $auditor = Auditor::where('user_id',auth()->user()->id)->first();
        $grups = new GrupAuditorList(GrupAuditor::with('auditor')->where('id', $auditor['grup_auditor_id'])->first());
        $auditee = Auditee::with('user.departemen.unit')->findOrFail($auditee_id);
        return response()->json([
            "grup_auditor" => $grups,
            "auditee" => $auditee,
        ], 200);
    }

    public function getSubDeptByAuditeeDept($dept_id){
        $sub_depts = SubDepartemen::where('departemen_id', $dept_id)->get();
        $data = [];
        if($sub_depts->count() > 0){
            foreach ($sub_depts as $item){
                $res = [
                    "label" => $item['nama_sub_departemen'],
                    "value" => $item['id']
                ];
                $data[] = $res;
            }
        }
        return response()->json($data, 200);
    }

    public function historyAuditDept($dept_id){
        $history = HeaderAudit::with('detail_audit', 'auditee.user.departemen.unit', 'departemen')->where('departemen_id', $dept_id)->get();
        if($history->count() > 0){
            return response()->json($history, 200);
        }else{
            return response()->json(['message' => 'Departemen Ini Belum Pernah Diaudit'], 404);
        }
    }

    public function auditorList(){
        $grup_auditor_count = GrupAuditor::count();
        $auditors = GrupAuditorList::collection(GrupAuditor::with('auditor')->paginate(10));
        $data = [
            "total_grup" => $grup_auditor_count,
            "data" => $auditors,
            "meta_paginate" => PaginateHelper::metaPaginateInfo($auditors),
        ];
        return response()->json($data, 200);
    }

    public function auditorListSelect(Request $request){
        $auditor_has_grup = null;
        if($request->has('grup_auditor_id') && $request->grup_auditor_id != ""){
            $auditor_has_grup = Auditor::with('user.departemen.unit')->where('grup_auditor_id', $request->grup_auditor_id)->get();
        }else{
            $auditors_no_grup = Auditor::with('user.departemen.unit')->where('grup_auditor_id', null)->where('keanggotaan', null)->get();
        }
        if($auditors_no_grup){
            foreach ($auditors_no_grup as $item){
                UserHelper::parseUserRole($item['user'], 'string');
            }
        }
        if($auditor_has_grup){
            foreach ($auditor_has_grup as $item){
                UserHelper::parseUserRole($item['user'], 'string');
            }
        }
        $data = [];
        if($auditors_no_grup){
            foreach ($auditors_no_grup as $item){
                $each = [
                    "label" => $item['user']['nama_lengkap'] . ' - '. $item['user']['departemen']['nama_departemen'] . ' - ' . $item['user']['departemen']['unit']['nama_unit'],
                    "value" => $item['user_id'],
                    "departemen" => $item['user']['departemen']['nama_departemen'],
                    "unit" => $item['user']['departemen']['unit']['nama_unit'],
                ];
                $data[] = $each;
            }
        }
        if($auditor_has_grup){
            foreach ($auditor_has_grup as $item){
                $each = [
                    "label" => $item['user']['nama_lengkap'] . ' - '. $item['user']['departemen']['nama_departemen'] . ' - ' . $item['user']['departemen']['unit']['nama_unit'],
                    "value" => $item['user_id'],
                    "departemen" => $item['user']['departemen']['nama_departemen'],
                    "unit" => $item['user']['departemen']['unit']['nama_unit'],
                ];
                $data[] = $each;
            }
        }
        return response()->json($data, 200);
    }

    public function historiAudit(Request $request){
        $auditor = Auditor::where('user_id', auth()->user()->id)->first();
        if($request->has('search') && $request->search != ""){
            $my_audits_count = HeaderAudit::where('no_plpp', 'like', '%'.$request->search.'%')->where('grup_auditor_id', $auditor['grup_auditor_id'])->count();
            $my_audits = MyAuditList::collection(HeaderAudit::with('detail_audit')->where('grup_auditor_id', $auditor['grup_auditor_id'])->where('no_plpp', 'like', '%'.$request->search.'%')->latest()->paginate(5));
        }else{
            $my_audits_count = HeaderAudit::where('grup_auditor_id', $auditor['grup_auditor_id'])->count();
            $my_audits = MyAuditList::collection(HeaderAudit::with('detail_audit')->where('grup_auditor_id', $auditor['grup_auditor_id'])->latest()->paginate(5));
        }
        if($my_audits){
            foreach ($my_audits as $item){
                $item['static_data'] = json_decode($item['static_data']);
            }
        }
        $data = [
            "my_audits_count" => $my_audits_count,
            'my_audits' => $my_audits,
            'meta' => PaginateHelper::metaPaginateInfo($my_audits),
        ];
        return response()->json($data, 200);
    }

    public function historiAuditDetail($id){
        $data = HeaderAudit::with('detail_audit.judul_clausul','detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->findOrFail($id);
        $data['static_data'] = json_decode($data['static_data']);
        foreach ($data['detail_audit'] as $item){
            $item['attachment'] = json_decode($item['attachment']);
            $item['static_data'] = json_decode($item['static_data']);
        }
        return response()->json($data, 200);
    }

    public function historiAuditEdit($id){
        $header = HeaderAudit::with('auditee.user', 'departemen.unit')->findOrFail($id);
        $detail = DetailAudit::with('judul_clausul', 'clausul', 'sub_clausul', 'sub_departemen')->where('header_audit_id', $id)->get();
        return response()->json([
            'header_audit' => AuditorHelper::handleEditGetAuditHeader($header),
            'detail_audit' => $detail ? AuditorHelper::handleEditGetAuditDetail($detail) :  null,
        ], 200);
    }

    public function destroy($id){
        $header_audit = HeaderAudit::findOrFail($id);
        if($header_audit){
            $header_audit->delete();
        }
        return response()->json(['message' => 'Berhasil Menghapus Sebuah Proses Audit'], 200);
    }
}
