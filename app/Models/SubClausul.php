<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubClausul extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
