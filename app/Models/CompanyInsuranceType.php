<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyInsuranceType extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function insuranceType()
    {
        return $this->belongsTo(InsuranceType::class);
    }

    public function insuranceCompany()
    {
        return $this->belongsTo(InsuranceCompany::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_name', 'id'); 
    }

    public function tehsil()
    {
        return $this->belongsTo(Tehsil::class, 'tehsil_id', 'id');
    }
}
