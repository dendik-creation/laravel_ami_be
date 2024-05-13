<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartemenList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    private function checkPeriode($month){
        if($month > 6){
            return 2;
        }else{
            return 1;
        }
    }

    public function toArray(Request $request): array
    {
        $year_now = date('Y');
        $periode_now = $this->checkPeriode(date('n'));
        return [
            'departemen' => $this->nama_departemen,
            'unit' => $this->unit->nama_unit,
            'audit_count' => $this->header_audit->whereStrict('periode', $request->periode ?? $periode_now)->whereStrict('tahun', $request->tahun ?? $year_now)->count(),
        ];
    }
}
