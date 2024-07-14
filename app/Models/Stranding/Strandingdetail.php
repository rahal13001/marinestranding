<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Strandingdetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'strandingreport_id',
       
        'latitude',
        'longitude',
        'method_id',
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

    ];

    public function strandingreport()
    {
        return $this->belongsTo(Strandingreport::class);
    }


    public function method()
    {
        return $this->belongsTo(Method::class);
    }

    

}
