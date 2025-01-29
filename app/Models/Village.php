<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;
    protected $guarded=[];
    
    public function uc()
    {
        return $this->belongsTo(Uc::class);
    }
}
