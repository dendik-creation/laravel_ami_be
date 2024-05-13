<?php

namespace App\Http\Controllers;

use App\Helpers\AuditorHelper;
use App\Helpers\AuditeeHelper;
use App\Helpers\PaginateHelper;
use App\Http\Resources\AuditNotYetResponded;
use App\Http\Resources\AuditNotYetRespondedCount;
use App\Http\Resources\GrupAuditorList;
use App\Http\Resources\MyAuditList;
use App\Models\Auditee;
use App\Models\HeaderAudit;
use App\Models\Auditor;
use App\Models\DetailAudit;
use App\Models\GrupAuditor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuditeeController extends Controller
{
    public function dashboard()
    {
        $auditee = Auditee::where('user_id', auth()->user()->id)->first();
        if ($auditee) {
            $auditor_count = Auditor::count();
            $audit_notyet_responded_count = AuditNotYetRespondedCount::collection(
                HeaderAudit::with('detail_audit')
                    ->where('is_responded', 0)
                    ->where('auditee_id', $auditee['id'])
                    ->get(),
            );
            $my_audit_count = HeaderAudit::where('auditee_id', $auditee['id'])->count();
            $most_grup_auditor_audit_me = AuditeeHelper::onlyMostFiveAuditMe(
                HeaderAudit::with('grup_auditor.auditor.user.departemen.unit')
                    ->where('auditee_id', $auditee['id'])
                    ->take(5)
                    ->get()
                    ->groupBy('grup_auditor_id'),
            );
            return response()->json(
                [
                    'auditor_count' => $auditor_count,
                    'audit_notyet_respon' => [
                        'total_header_audit' => $audit_notyet_responded_count->count(),
                        'total_detail_audit' => $audit_notyet_responded_count,
                    ],
                    'my_audit_count' => $my_audit_count,
                    'most_grup_auditor_audit_me' => $most_grup_auditor_audit_me,
                ],
                200,
            );
        }
    }

    public function responAuditGet()
    {
        $now = Carbon::now();
        $auditee = Auditee::where('user_id', auth()->user()->id)->first();
        $data = AuditNotYetResponded::collection(
            HeaderAudit::with('departemen.unit', 'iso', 'grup_auditor.auditor.user.departemen', 'detail_audit')
                ->where('auditee_id', $auditee['id'])
                ->where('is_responded', 0)
                ->latest()
                ->get(),
        );
        // Apakah Punya Temuan
        foreach ($data as $index => $item) {
            $item['static_data'] = json_decode($item['static_data']);
            if ($item['detail_audit']) {
                if ($item['detail_audit']->count() == 0) {
                    unset($data[$index]);
                } else {
                    foreach ($item['detail_audit'] as $each_detail) {
                        $each_detail['static_data'] = json_decode($each_detail['static_data']);
                    }
                }
            }
        }
        return response()->json($data, 200);
    }

    public function responAuditGetOne($id)
    {
        $data = new AuditNotYetResponded(HeaderAudit::with('departemen.unit', 'auditee.user.departemen.unit', 'iso', 'grup_auditor.auditor.user.departemen.unit', 'detail_audit')->findOrFail($id));
        $data['static_data'] = json_decode($data['static_data']);
        foreach ($data['detail_audit'] as $item) {
            $item['static_data'] = json_decode($item['static_data']);
            $item['attachment'] = json_decode($item['attachment']);
        }
        return response()->json($data, 200);
    }

    public function responAuditUpdate(Request $request, $header_id)
    {
        $response = [];
        foreach ($request->data as $item) {
            array_push($response, AuditeeHelper::responAuditAction($item));
        }
        $header = HeaderAudit::findOrFail($header_id);
        $header->update(['is_responded' => 1]);
        return response()->json($response, 200);
    }

    public function responAuditUpdateDocs(Request $request)
    {
        // return $request;
        $request->validate(
            [
                'data.*.files.*' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg|max:2048',
            ],
            [
                'data.*.files.*.mimes' => 'Ada beberapa file yang tidak sesuai format',
                'data.*.files.*.max' => 'Ada beberapa file yang melebihi batas maksimum',
            ],
        );
        $auditee = Auditee::where('user_id', auth()->user()->id)->first();
        if ($request->data) {
            foreach ($request->data as $eaches) {
                $detail = DetailAudit::findOrFail($eaches['detail_id']);
                if ($auditee && $detail) {
                    if ($detail['attachment'] != null) {
                        $detail['attachment'] = json_decode($detail['attachment']);
                        foreach ($detail['attachment'] as $item) {
                            if (Storage::exists('public/' . $item)) {
                                Storage::delete('public/' . $item);
                            }
                        }
                    }
                    $attach = [];
                    foreach ($eaches['files'] as $file) {
                        if ($file->isValid()) {
                            $filename = $file->getClientOriginalName();
                            $file->storeAs('public/respon_audit/auditee-id-' . $auditee['id'] . '/temuan-id-' . $detail['id'], $filename);
                            $attach[] = '/respon_audit/auditee-id-' . $auditee['id'] . '/temuan-id-' . $detail['id'] . '/' . $filename;
                        }
                    }
                    $detail->update(['attachment' => json_encode($attach)]);
                }
            }
            return response()->json(['message' => 'File berhasil diupload'], 200);
        }
    }

    public function auditorList()
    {
        $auditors = GrupAuditorList::collection(GrupAuditor::with('auditor')->paginate(5));
        $data = [
            'data' => $auditors,
            'meta_paginate' => PaginateHelper::metaPaginateInfo($auditors),
        ];
        return response()->json($data, 200);
    }

    public function historiAudit(Request $request)
    {
        $auditee = Auditee::where('user_id', auth()->user()->id)->first();
        if($request->has('search') && $request->search != ""){
            $my_audits_count = HeaderAudit::where('no_plpp', 'like', '%'.$request->search.'%')->where('auditee_id', $auditee['id'])->count();
            $my_audits = MyAuditList::collection(
                HeaderAudit::with('iso', 'auditee.user.departemen', 'departemen', 'detail_audit')
                    ->where('auditee_id', $auditee['id'])
                    ->where('no_plpp', 'like', '%'.$request->search.'%')
                    ->latest()
                    ->paginate(5),
            );
        }else{
            $my_audits_count = HeaderAudit::where('auditee_id', $auditee['id'])->count();
            $my_audits = MyAuditList::collection(
                HeaderAudit::with('iso', 'auditee.user.departemen', 'departemen', 'detail_audit')
                    ->where('auditee_id', $auditee['id'])
                    ->latest()
                    ->paginate(5),
            );
        }
        if($my_audits){
            foreach ($my_audits as $item){
                $item['static_data'] = json_decode($item['static_data']);
                // unset($item['detail_audit']);
            }
        }
        $data = [
            'my_audits_count' => $my_audits_count,
            'my_audits' => $my_audits,
            'meta' => PaginateHelper::metaPaginateInfo($my_audits),
        ];
        return response()->json($data, 200);
    }

    public function historiAuditDetail($id)
    {
        $data = HeaderAudit::with('detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->findOrFail($id);
        $data['static_data'] = json_decode($data['static_data']);
        foreach ($data['detail_audit'] as $item){
            $item['attachment'] = json_decode($item['attachment']);
            $item['static_data'] = json_decode($item['static_data']);
        }
        return response()->json($data, 200);
    }

    private function getPeriode()
    {
        $month = date('n');
        return $month > 6 ? 2 : 1;
    }

    public function historiAuditByDept($dept_id)
    {
        $getNowPeriode = $this->getPeriode();
        if ($getNowPeriode == 1) {
            $audits = HeaderAudit::with('grup_auditor.auditor', 'iso', 'auditee.user.departemen.unit', 'departemen', 'detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')
                ->where('departemen_id', $dept_id)
                ->where('tahun', intval(date('Y')) - 1)
                ->where('periode', 2)
                ->latest()
                ->get();
            $returnPeriode = 'Periode 2 - ' . intval(date('Y')) - 1;
        } elseif ($getNowPeriode == 2) {
            $audits = HeaderAudit::with('grup_auditor.auditor', 'iso', 'auditee.user.departemen.unit', 'departemen', 'detail_audit.judul_clausul', 'detail_audit.clausul', 'detail_audit.sub_clausul', 'detail_audit.sub_departemen')->where('departemen_id', $dept_id)->where('tahun', date('Y'))->where('periode', 1)->latest()->get();
            $returnPeriode = 'Periode 1 - ' . intval(date('Y'));
        }
        return response()->json(
            [
                'periode' => $returnPeriode,
                'data' => $audits,
            ],
            200,
        );
    }

    public function downloadAttachment(Request $request)
    {
        if (Storage::disk('local')->exists('public/' . $request->pathfile)) {
            $data = Storage::disk('local')->get('public/' . $request->pathfile);

            $mime = Storage::mimeType($data);
            $response = response($data, 200);
            $response->header('Content-Type', $mime);

            return $response;
        } else {
            return response()->json(['message' => 'File Tidak Ditemukan'], 404);
        }
    }

    public function getAttachment(Request $request)
    {
        $filePath = $request->input('pathfile');
        return 'http://ami_be.local/storage/' . $filePath;
    }
}
