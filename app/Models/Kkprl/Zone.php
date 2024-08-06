<?php

namespace App\Models\Kkprl;

use App\Models\Stranding\Province;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['zone_name', 'province_id', 'description'];

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function kkprlmaps()
    {
        return $this->hasMany(Kkprlmap::class);
    }

    public function activityzone()
    {
        return $this->hasMany(Activityzone::class);
    }

}
