<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['activity_name'];

    public function activityZones()
    {
        return $this->hasMany(ActivityZone::class);
    }
}
