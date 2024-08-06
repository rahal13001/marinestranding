<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['activity_name'];

    public function activityZones()
    {
        return $this->hasMany(ActivityZone::class);
    }
}
