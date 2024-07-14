<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class StrandingreportUser extends Pivot
{
    use HasFactory;

    protected $fillable = [
        'strandingreport_id',
        'user_id',
    ];


}
