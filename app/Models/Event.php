<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Khali jaga wale fields
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'start_time',
    ];

    // EventImages se taaluq (1 Event -> Zyada Images)
    public function eventImages()
    {
        return $this->hasMany(EventImage::class, 'event_id');
    }

    // Cover Image ke liye rishta
    public function coverImage()
    {
        return $this->hasOne(EventImage::class)->where('is_cover', true);
    }
}