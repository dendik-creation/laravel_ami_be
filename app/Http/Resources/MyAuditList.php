<?php

namespace App\Http\Resources;

use App\Models\GrupAuditor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyAuditList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     private function getKategoriOnTemuan($details){
        $results = [];
        if($details){
            foreach ($details as $item){
                $results[]= $item['kategori'];
            }
        }
        return $results;
     }

    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
    $data['grup_auditor'] = new GrupAuditorList(GrupAuditor::with('auditor')->findOrFail($this->grup_auditor_id));
    $data['kategori_temuan'] = $this->getKategoriOnTemuan($data['detail_audit']);
    unset($data['detail_audit']);
    return $data;
    }
}
