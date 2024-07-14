<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Family extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kelas',
        'ordo',
        'family',
    ];

    public function genera()
    {
        return $this->hasOne(Genera::class);
    }
    
}
