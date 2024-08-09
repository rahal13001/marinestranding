<?php

namespace App\Models\Kkprl;

use App\Models\Stranding\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kkprluse extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'province_id',
        'subject_shp',
        'subject_activity',
        'color',
        'subject_status',
        'width',
        'length',
        'latitude',
        'longitude',
        'subject_name',
        'shp_type',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }
}
