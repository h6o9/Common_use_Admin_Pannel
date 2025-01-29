<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealerItem extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function authorizedDealer()
    {
        return $this->belongsTo(AuthorizedDealer::class);
    }
}
