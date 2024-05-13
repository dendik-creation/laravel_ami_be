<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditNotYetResponded extends JsonResource
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
        $header_audit = $this->resource->toArray();
        new GrupAuditorList($header_audit['grup_auditor']);
        $header_audit['kategori_temuan'] = $this->getKategoriOnTemuan($header_audit['detail_audit']);
        unset($header_audit['detail_audit']);
        $detail_audit = $this->resource['detail_audit'];
        return [
            "header_audit" => $header_audit,
            "detail_audit" => $detail_audit->loadMissing('sub_departemen', 'judul_clausul', 'clausul', 'sub_clausul'),
        ];
    }
}
