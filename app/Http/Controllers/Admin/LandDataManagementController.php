<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use Illuminate\Http\Request;
use App\Models\InsuranceType;
use App\Models\InsuranceSubType;
use App\Models\LandDataManagement;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LandDataManagementController extends Controller
{
    public function index()
    {
        $lands = District::orderBy('name', 'asc')->get();
        // dd($lands);
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }
        return view('admin.land.index', compact('lands', 'sideMenuPermissions', 'sideMenuName'));
    }

    public function getInsuranceTypes($companyId)
    {
        $insuranceTypes = InsuranceType::where('incurance_company_id', $companyId)->where('status', 1)->get();

        return response()->json($insuranceTypes);
    }

    public function getInsuranceSubTypes($typeId)
    {
        $insuranceSubTypes = InsuranceSubType::where('incurance_type_id', $typeId)->where('status', 1)->get();

        return response()->json($insuranceSubTypes);
    }
}
