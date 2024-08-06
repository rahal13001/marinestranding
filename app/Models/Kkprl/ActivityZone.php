<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityZone extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['activity_id', 'zone_id', 'activitystatus_id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function activitystatus()
    {
        return $this->belongsTo(Activitystatus::class);
    }
}
