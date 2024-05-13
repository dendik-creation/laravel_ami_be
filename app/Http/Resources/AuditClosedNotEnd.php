<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditClosedNotEnd extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

     private function onlyDetailNotNull($header){
        if($header['detail_audit']->whereStrict('status', "close")->whereStrict('tindakan', null)->where('analisa', null)->loadMissing('sub_departemen', 'judul_clausul', 'clausul', 'sub_clausul')->count() > 0){
            return $header;
        }else{
            return null;
        };
     }

    public function toArray(Request $request)
    {
        $header_audit = $this->resource;
        return $this->onlyDetailNotNull($header_audit);
    }
}
