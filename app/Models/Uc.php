<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uc extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class);
    }

    public function villages()
    {
        return $this->hasMany(Village::class);
    }
}
