<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Method extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'method',
    ];

    public function strandingdetails()
    {
        return $this->hasMany(Strandingdetail::class);
    }
}
