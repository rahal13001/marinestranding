<?php

namespace App\Models\Stranding;

use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Species extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'genera_id',
        'species',
        'local_name',
        'description',
        'slug'
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
}
