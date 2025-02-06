<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\InsuranceSubType;
use App\Http\Controllers\Controller;
use App\Models\CompanyInsuranceType;
use Illuminate\Support\Facades\Auth;
use App\Models\CompanyInsuranceSubType;

class CompanyInsuranceSubTypeController extends Controller
{
    public function index($id)
    {
        $CompanyinsuranceType = CompanyInsuranceType::with('insuranceType')->with('insuranceCompany')->find($id);
        // dd($CompanyinsuranceType);
        // Insurance Sub-types data belongs to Compnies
        $CompanyInsurancesSubTypes = CompanyInsuranceSubType::where('company_insurance_type_id', $id)->orderBy('status', 'desc')->latest()->get();

        // Insurance Names from Insurance Sub-types
        $InsuranceSubTypeIds = CompanyInsuranceSubType::pluck('insurance_subtype_id')->toArray();
        $InsuranceSubTypeNames = InsuranceSubType::whereIn('id', $InsuranceSubTypeIds)->get()->keyBy('id');
        // dd($InsuranceSubTypeNames);

        // Which are saved against company not showing again in dropdown
        $savedInsuranceSubtypeIds = CompanyInsuranceSubType::where('company_insurance_type_id', $id)
            ->pluck('insurance_subtype_id')
            ->toArray();
        $savedInsuranceSubtypes = InsuranceSubType::where('incurance_type_id', $CompanyinsuranceType->insurance_type_id)
            ->whereNotIn('id', $savedInsuranceSubtypeIds)
            ->orderBy('name', 'asc')
            ->get();
        // dd($savedInsuranceSubtypes);

        // Permissions 
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        return view('admin.company_insurance.sub_type', compact('sideMenuPermissions', 'sideMenuName', 'CompanyInsurancesSubTypes', 'savedInsuranceSubtypes', 'CompanyinsuranceType', 'InsuranceSubTypeNames'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'company_insurance_type_id' => 'required',
            'insurance_subtype_id' => 'required|array',
            'price' => 'required|array',
            'price.*' => 'required|numeric|min:1',
        ]);
        // dd($request);

        // // Create a new record
        foreach ($request->insurance_subtype_id as $insuranceSubTypeId) {
            CompanyInsuranceSubtype::create([
                'company_insurance_type_id' => $request->company_insurance_type_id,
                'insurance_subtype_id' => $insuranceSubTypeId,
                'price' => $request->price[$insuranceSubTypeId] ?? 0,
                'status' => $request->status
            ]);
        }

        // // Return success message
        return redirect()->route('company.insurance.sub.types.index', ['id' => $request->company_insurance_type_id])
            ->with(['message' => 'Company Insurance Sub-Type Created Successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'price' => 'required',
            'price.*' => 'required|numeric|min:1',
        ]);

        $data = CompanyInsuranceSubtype::findOrFail($id);
        $data->update([
            'status' => $request->status,
            'price' => $request->price
        ]);

        return redirect()->route('company.insurance.sub.types.index', ['id' => $request->company_insurance_type_id])
            ->with(['message' => 'Company Insurance Sub-Type Status Update Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        // dd($request);
        CompanyInsuranceSubtype::destroy($id);
        return redirect()->route('company.insurance.sub.types.index', ['id' => $request->company_insurance_type_id])
            ->with(['message' => 'Company Insurance Sub-Type Deleted Successfully']);
    }
}
