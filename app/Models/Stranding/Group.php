<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'group_name',
        'icon',
    ];

    public function strandingreport()
    {
        return $this->hasOne(Strandingreport::class);
    }
}
