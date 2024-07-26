<?php

namespace App\Models\Kkprl;

use App\Models\Stranding\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = ['zone_name', 'province_id', 'description'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function kkprlmaps()
    {
        return $this->hasMany(Kkprlmap::class);
    }

}
