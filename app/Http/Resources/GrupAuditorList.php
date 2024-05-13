<?php

namespace App\Http\Resources;

use App\Helpers\UserHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupAuditorList extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    private function leadFirstThenMember($data){
        $array = $data->toArray();
        usort($array, function ($a, $b) {
            if ($a['keanggotaan'] === 'ketua') {
                return -1;
            } elseif ($b['keanggotaan'] === 'ketua') {
                return 1;
            } else {
                return 0;
            }
        });
        return $array;
    }
    public function toArray(Request $request): array | string
    {
        return [
            "id" => $this->id,
            "nama_grup" => $this->nama_grup,
            "jumlah_anggota" => $this->auditor->count() == 0 ? 0 : $this->auditor->count() - 1,
            "auditor_list" => $this->leadFirstThenMember(UserHelper::parseUserRole($this->auditor->loadMissing('user.departemen.unit'), "array_auditor_list")),
        ];
    }
}
