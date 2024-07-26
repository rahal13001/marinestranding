<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activitystatus extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function activityZones()
    {
        return $this->hasMany(ActivityZone::class);
    }
}
