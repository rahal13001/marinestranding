<?php

namespace App\Models\Kkprl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regulation extends Model
{
    use HasFactory;

    protected $fillable = ['regulation_name', 'regulation'];

    public function kkprlmaps()
    {
        return $this->hasMany(Kkprlmap::class);
    }
}
