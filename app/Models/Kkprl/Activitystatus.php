<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activitystatus extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['status'];

    public function activityZones()
    {
        return $this->hasMany(ActivityZone::class);
    }
}
