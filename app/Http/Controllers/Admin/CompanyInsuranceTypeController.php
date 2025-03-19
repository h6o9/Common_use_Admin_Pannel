<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tehsil;
use App\Models\District;
use Illuminate\Http\Request;
use App\Models\InsuranceType;
use App\Models\EnsuredCropName;
use App\Models\InsuranceCompany;
use App\Models\InsuranceSubType;
use App\Http\Controllers\Controller;
use App\Models\CompanyInsuranceType;
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
        $ensuredCrops = EnsuredCropName::all();  // Fetch all crops
        $districts = District::all();         // Fetch all districts
        $tehsils = Tehsil::all();
        
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        return view('admin.company_insurance.type', compact('sideMenuPermissions', 'sideMenuName', 'CompanyInsurances', 'Company', 'Insurance_types','ensuredCrops', 'districts', 'tehsils'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'insurance_company_id' => 'required',
            'insurance_type_id' => 'required|array',
        ]);
        // dd($request);
        // dd($request->all()); // Check if the input structure is correct

        foreach ($request->crop as $index => $crop) {
            foreach ($request->insurance_type_id as $insuranceTypeId) {
                
                CompanyInsuranceType::create([
                    'insurance_company_id' => $request->insurance_company_id,
                    'insurance_type_id' => $insuranceTypeId,
                    'crop' => $crop,
                    'district_name' => $request->district_name[$index] ?? null,
                    'tehsil' => $request->tehsil[$index] ?? null,
                    'benchmark' => isset($request->benchmark[$index] ) 
                        ? implode("\n", $request->benchmark[$index]) 
                        : null,
                    'price_benchmark' => isset($request->price_benchmark[$index]) 
                        ? implode("\n", $request->price_benchmark[$index]) 
                        : null,
                ]);
        }
    }
        // Return success message
        return redirect()->route('company.insurance.types.index', ['id' => $request->insurance_company_id])->with(['message' => 'Company Insurance Created Successfully']);
    }

    public function update(Request $request, $id)
    {

        try {
            // Find Company Insurance Type
            $company = CompanyInsuranceType::findOrFail($id);
    
            // Update main fields
            $company->update([
                'crop'           => is_array($request->crop) ? implode(',', $request->crop) : $request->crop,
                'district_name'  => is_array($request->district_name) ? implode(',', $request->district_name) : $request->district_name,
                'tehsil'         => is_array($request->tehsil) ? implode(',', $request->tehsil) : $request->tehsil,
            
            ]);
    
            // Update Benchmarks
            $benchmarks = $request->benchmark[$id] ?? [];
            $priceBenchmarks = $request->price_benchmark[$id] ?? [];
    
            // Store as newline-separated values (or customize storage as needed)
            $company->benchmark = implode("\n", array_filter($benchmarks));
            $company->price_benchmark = implode("\n", array_filter($priceBenchmarks));
            $company->save();
    
            // DB::commit();
    
            return redirect()
                ->route('company.insurance.types.index', ['id' => $request->incurance_company_id])
                ->with(['message' => 'Company Insurance Updated Successfully']);
        } catch (\Exception $e) {
            // DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    
    }

    public function destroy(Request $request, $id)
    {
        // dd($request);
        CompanyInsuranceType::destroy($id);
        return redirect()->route('company.insurance.types.index', ['id' => $request->incurance_company_id])->with(['message' => 'Company Insurance Deleted Successfully']);
    }

}
