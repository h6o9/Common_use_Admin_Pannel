<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\CompanyInsurance;
use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use App\Models\InsuranceSubType;
use App\Models\InsuranceType;
use Illuminate\Support\Facades\Auth;

class CompanyInsuranceController extends Controller
{
    public function index($id)
    {
        $Company = InsuranceCompany::find($id);
        $CompanyInsurances = CompanyInsurance::where('insurance_company_id', $id)->latest()->get();

        // for name show at index page
        $Insurance_type_ids = $CompanyInsurances->pluck('insurance_type_id');
        $Insurance_type_names = InsuranceType::whereIn('id', $Insurance_type_ids)->get();

        // for subtypes at index page
        // $Insurance_subtype_ids = $CompanyInsurances->pluck('insurance_subtype_id');
        // $Insurance_subtype_names = InsuranceSubType::whereIn('id', $Insurance_subtype_ids)->get();
        $Insurance_subtype_ids = $CompanyInsurances->pluck('insurance_subtype_id')->toArray();
        $Insurance_subtype_ids = collect($Insurance_subtype_ids)
            ->map(fn($item) => json_decode($item, true)) // Decode JSON
            ->flatten() // Flatten into a single array
            ->filter() // Remove nulls
            ->unique() // Remove duplicates
            ->toArray();

        $Insurance_subtype_names = InsuranceSubType::whereIn('id', $Insurance_subtype_ids)->get()->keyBy('id');
        // dd($Insurance_subtype_names);

        // for creating time types
        $Insurance_types = InsuranceType::where('status', '=', 1)->orderBy('name', 'asc')->get();
        // dd($CompanyInsurances);

        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        return view('admin.company_insurance.index', compact('sideMenuPermissions', 'sideMenuName', 'CompanyInsurances', 'Company', 'Insurance_types', 'Insurance_type_names', 'Insurance_subtype_names'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'insurance_company_id' => 'required',
            'insurance_type_id' => 'required',
            'insurance_subtype_id' => 'nullable',
        ]);
        // dd($request);

        // // Create a new record
        CompanyInsurance::create([
            'insurance_company_id' => $request->insurance_company_id,
            'insurance_type_id' => $request->insurance_type_id,
            'insurance_subtype_id' => json_encode($request->insurance_subtype_id),
        ]);

        // // Return success message
        return redirect()->route('company.insurance.index', ['id' => $request->insurance_company_id])->with(['message' => 'Company Insurance Created Successfully']);
    }

    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required|string',
        //     'quantity' => 'nullable|string',
        //     'price' => 'nullable|string',
        // ]);
        // // dd($request);

        // $item = DealerItem::findOrFail($id);

        // $item->update([
        //     'name' => $request->name,
        //     'quantity' => $request->quantity,
        //     'price' => $request->price,
        //     'status' => $request->status,
        // ]);

        // return redirect()->route('dealer.item.index', ['id' => $request->dealer_id])->with(['message' => 'Item Updated Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        // dd($request);
        // DealerItem::destroy($id);
        // return redirect()->route('dealer.item.index', ['id' => $request->dealer_id])->with(['message' => 'Item Deleted Successfully']);
    }

    public function GetSubType(Request $request)
    {
        $data = InsuranceSubType::where('incurance_type_id', $request->insuranceType_Id)->where('status', '=', 1)->get();
        // dd($data);
        return response()->json($data);
    }
}
