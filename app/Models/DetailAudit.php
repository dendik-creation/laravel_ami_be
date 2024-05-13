<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAudit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function header_audit(){
        return $this->belongsTo(HeaderAudit::class);
    }

    public function sub_departemen(){
        return $this->belongsTo(SubDepartemen::class);
    }

    public function judul_clausul(){
        return $this->belongsTo(JudulClausul::class);
    }

    public function clausul(){
        return $this->belongsTo(Clausul::class);
    }

    public function sub_clausul(){
        return $this->belongsTo(SubClausul::class);
    }
}
