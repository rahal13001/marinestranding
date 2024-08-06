<?php

namespace App\Models\Kkprl;

use App\Models\Stranding\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kkprlmap extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $fillable = ['zone_id', 'province_id', 'shp', 'color'];

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function regulation()
    {
        return $this->belongsTo(Regulation::class);
    }

    
}
