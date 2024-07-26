<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityZone extends Model
{
    use HasFactory;

    protected $fillable = ['activity_id', 'zone_id', 'regulationstatus_id'];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    public function activityStatus()
    {
        return $this->belongsTo(Activitystatus::class);
    }
}
