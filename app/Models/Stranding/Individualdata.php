<?php

namespace App\Models\Stranding;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Individualdata extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'strandingreport_id',
        'code_id',
        'gender',
        'total_length',
        'method_id',
        'ind_desc',
    ];

    public function code()
    {
        return $this->belongsTo(Code::class);
    }

    public function method()
    {
        return $this->belongsTo(Method::class);
    }

}
