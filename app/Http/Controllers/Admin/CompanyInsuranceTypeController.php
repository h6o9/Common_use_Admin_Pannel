<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CompanyInsuranceType;
use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use App\Models\InsuranceSubType;
use App\Models\InsuranceType;
use Illuminate\Support\Facades\Auth;

class CompanyInsuranceTypeController extends Controller
{
    public function index($id)
    {
        $Company = InsuranceCompany::find($id);

        $CompanyInsurances = CompanyInsuranceType::where('insurance_company_id', $id)->with('insuranceType')->orderBy('status', 'desc')->latest()->get();
        // dd($CompanyInsurances);

        // for creating time types
        $savedInsuranceTypeIds = CompanyInsuranceType::where('insurance_company_id', $id)
            ->pluck('insurance_type_id')
            ->toArray();
        $Insurance_types = InsuranceType::where('status', 1)
            ->whereNotIn('id', $savedInsuranceTypeIds)
            ->orderBy('name', 'asc')
            ->get();

        // dd($CompanyInsurances);

        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        return view('admin.company_insurance.type', compact('sideMenuPermissions', 'sideMenuName', 'CompanyInsurances', 'Company', 'Insurance_types'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'insurance_company_id' => 'required',
            'insurance_type_id' => 'required|array',
        ]);
        // dd($request);

        // Create a new record
        foreach ($request->insurance_type_id as $insuranceTypeId) {
            CompanyInsuranceType::create([
                'insurance_company_id' => $request->insurance_company_id,
                'insurance_type_id' => $insuranceTypeId,
                'price' => $request->price[$insuranceTypeId] ?? 0,
                'status' => $request->status
            ]);
        }

        // Return success message
        return redirect()->route('company.insurance.types.index', ['id' => $request->insurance_company_id])->with(['message' => 'Company Insurance Created Successfully']);
    }

    public function update(Request $request, $id)
    {

        $company = CompanyInsuranceType::findOrFail($id);

        $company->update([
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('company.insurance.types.index', ['id' => $request->incurance_company_id])->with(['message' => 'Company Insurance Status Update Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        // dd($request);
        CompanyInsuranceType::destroy($id);
        return redirect()->route('company.insurance.types.index', ['id' => $request->incurance_company_id])->with(['message' => 'Company Insurance Deleted Successfully']);
    }

}
