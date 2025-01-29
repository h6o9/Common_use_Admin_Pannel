<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsuranceCompany extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function insuranceTypes()
    {
        return $this->hasMany(InsuranceType::class, 'incurance_company_id');
    }

}
