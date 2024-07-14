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

    protected $fillable = [
        'user_id',
        'province_id',
        'location',
        'group_id',
        'category_id',
        'informant_name',
        'partner',
        'species_id',
        'quantity_id',
        'count',
        'information_date',
        'code_id',
        'gender',
        'latitude',
        'longitude',
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

    
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['information_date', 'location', 'category.category'])
            ->saveSlugsTo('slug');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
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
        return $this->belongsTo(Province::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function species()
    {
        return $this->belongsTo(Species::class);
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
