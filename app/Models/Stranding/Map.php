<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    use HasFactory;

    protected $fillable = [
        'strandingreport_id',
        'location',
        'province_id',
        'information_date',
        'map_slug',
        'category_id',
        'species_id',
        'group_id',
        'latitude',
        'longitude',
    ];

    public function strandingreport()
    {
        return $this->belongsTo(Strandingreport::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function species()
    {
        return $this->belongsTo(Species::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function code()
    {
        return $this->belongsTo(Code::class);
    }

}
