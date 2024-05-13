<?php

namespace App\Http\Controllers;

use App\Helpers\PaginateHelper;
use App\Models\HeaderAudit;
use App\Helpers\PDDHelper;
use App\Helpers\AuditorHelper;
use App\Helpers\UserHelper;
use App\Http\Resources\DepartemenList;
use App\Http\Resources\MyAuditList;
use App\Models\Departemen;
use App\Models\User;
use Illuminate\Http\Request;

class PDDController extends Controller
{
    public function dashboard(Request $request){
        $most_grup_auditor_audit = PDDHelper::onlyMostFiveGrupAudit(HeaderAudit::with('grup_auditor.auditor')->whereNot('grup_auditor_id', NULL)->take(5)->get()->groupBy('grup_auditor_id'));
        $all_audits = PDDHelper::countKategori(HeaderAudit::with('detail_audit')->get());
        $all_users = PDDHelper::countUserByRole(UserHelper::parseUserRole(User::all(), "array"));
        $dept_audited_not_responded = HeaderAudit::with('auditee.user', 'departemen.unit', 'grup_auditor')->where('is_responded', 0)->latest()->take(5)->get();
        $dept_sortby_not_audited = DepartemenList::collection(Departemen::with('header_audit')->get());
        return response()->json([
            "most_grup_auditor_audit" => $most_grup_auditor_audit,
            "all_audits" => $all_audits,
            "all_users" => $all_users,
            "dept_audited_not_responded" => $dept_audited_not_responded,
            "dept_sortby_not_audited" => $dept_sortby_not_audited,
        ], 200);
    }

    public function historiAudit(Request $request){
        if($request->has('search') && $request->search != ""){
            $all_audits_count = HeaderAudit::where('no_plpp', 'like', '%'.$request->search.'%')->count();
            $all_audits = MyAuditList::collection(HeaderAudit::with('detail_audit')->where('no_plpp', 'like', '%'.$request->search.'%')->latest()->paginate(5));
        }else{
            $all_audits_count = HeaderAudit::count();
            $all_audits = MyAuditList::collection(HeaderAudit::with('detail_audit')->latest()->paginate(5));
        }
        if($all_audits){
            foreach ($all_audits as $item){
                $item['static_data'] = json_decode($item['static_data']);
            }
        }

        $data = [
            "all_audits_count" => $all_audits_count,
            'all_audits' => $all_audits,
            'meta' => PaginateHelper::metaPaginateInfo($all_audits),
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

        return response()->json($data, 200);
    }
}
