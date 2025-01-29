<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnsuredCrop extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function cropName()
    {
        return $this->belongsTo(EnsuredCropName::class, 'crop_name_id', 'id');
    }
    public function cropType()
    {
        return $this->belongsTo(EnsuredCropType::class, 'crop_type_id', 'id');
    }
}
