<?php

namespace App\Models\Stranding;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;

class Species extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $fillable = [
        'genera_id',
        'species',
        'local_name',
        'description',
        'slug',
        'group_id',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['species', 'local_name'])
            ->saveSlugsTo('slug');
    }
    
    public function genera()
    {
        return $this->belongsTo(Genera::class);
    }

    public function strandingreports()
    {
        return $this->hasMany(Strandingreport::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
