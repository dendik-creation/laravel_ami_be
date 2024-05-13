<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeaderAudit extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        // 'created_at',
        'updated_at',
    ];

    public function auditee(){
        return $this->belongsTo(Auditee::class);
    }

    public function detail_audit(){
        return $this->hasMany(DetailAudit::class);
    }

    public function iso(){
        return $this->belongsTo(Iso::class);
    }

    public function grup_auditor(){
        return $this->belongsTo(GrupAuditor::class);
    }

    public function departemen(){
        return $this->belongsTo(Departemen::class);
    }
}
