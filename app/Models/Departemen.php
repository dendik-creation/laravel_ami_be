<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function unit(){
        return $this->belongsTo(Unit::class);
    }
    public function sub_departemen(){
        return $this->hasMany(SubDepartemen::class);
    }
    public function header_audit(){
        return $this->hasMany(HeaderAudit::class);
    }
}
