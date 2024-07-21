<?php

namespace App\Models\Stranding;

use App\Models\User;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Strandingreport extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'user_id',
        'informant_name',
        'partner',
        'quantity_id',
        'count',
        'code_id',
        'gender',
        'title',
        'report',
        'start_handling_date',
        'end_handling_date',
        'documentation1',
        'documentation2',
        'documentation3',
        'documentation4',
        'documentation5',
        'st',
        'other',
        'slug',

    ];

    protected static function boot()
    {
        parent::boot();

        // Listen for the 'saved' event
        static::saved(function ($strandingreport) {
            // Assuming the slug is automatically generated and saved in the 'slug' attribute
            $slug = $strandingreport->slug;

            // Save the slug to the related Map
            $map = $strandingreport->map()->firstOrCreate([]);
            $map->map_slug = $slug; // Make sure 'map_slug' is the correct column name in your 'maps' table
            $map->save();
        });
    }

    public function map()
    {
        return $this->hasOne(Map::class);
    }

    
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['information_date', 'location', 'category.map.category'])
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsToThrough(Category::class, Map::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'strandingreport_user', 'strandingreport_id', 'user_id');
    }

    public function strandingdetail()
    {
        return $this->hasOne(Strandingdetail::class);
    }

    public function province()
    {
        return $this->belongsToThrough(Province::class, Map::class);
    }

    public function group()
    {
        return $this->belongsToThrough(Group::class, Map::class);
    }

    public function species()
    {
        return $this->belongsToThrough(Species::class, Map::class);
    }

    public function quantity()
    {
        return $this->belongsTo(Quantity::class);
    }

    public function individualdatas()
    {
        return $this->hasMany(Individualdata::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
