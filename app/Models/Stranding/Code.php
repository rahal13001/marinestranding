<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Code extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'code',
        'code_mean',
    ];
}
