<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventImage extends Model
{
    use HasFactory;

    // Defining fillable fields
    protected $fillable = [
        'event_id',
        'image_path',
        'is_cover',
    ];

    // Relationship with Event (Inverse of the above relationship)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
