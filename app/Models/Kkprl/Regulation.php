<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regulation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['regulation_name', 'regulation_number', 'regulation'];

    public function kkprlmaps()
    {
        return $this->hasMany(Kkprlmap::class);
    }
}
