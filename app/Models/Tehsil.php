<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tehsil extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ucs()
    {
        return $this->hasMany(Uc::class);
    }
}
