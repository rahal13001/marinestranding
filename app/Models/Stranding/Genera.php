<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genera extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'family_id',
        'genera',
    ];

    public function species()
    {
        return $this->hasOne(Species::class);
    }

    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}
