<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoged extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_lengkap' => $this->nama_lengkap,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'periode_active_role' => $this->periode_active_role,
            'departemen' => $this->departemen->loadMissing('unit'),
        ];
    }
}
