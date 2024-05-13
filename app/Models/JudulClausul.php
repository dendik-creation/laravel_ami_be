<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudulClausul extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function iso(){
        return $this->belongsTo(Iso::class);
    }
    public function clausul(){
        return $this->hasMany(Clausul::class);
    }
}
