<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditNotYetRespondedCount extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request)
    {
        $notyet_respond = $this->detail_audit;
        $filtered = $notyet_respond->whereStrict('analisa', null)->whereStrict('tindakan', null)->whereStrict('status', "open");
        return $filtered->count();
    }
}
