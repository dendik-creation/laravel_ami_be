<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Auditor extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function grup_auditor(){
        return $this->belongsTo(GrupAuditor::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
