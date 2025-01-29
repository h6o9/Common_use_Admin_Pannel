<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorizedDealer extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function dealerItems()
    {
        return $this->hasMany(DealerItem::class);
    }
}
