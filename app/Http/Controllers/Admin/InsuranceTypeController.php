<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\InsuranceType;
use App\Http\Controllers\Controller;
use App\Models\InsuranceCompany;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class InsuranceTypeController extends Controller
{
    public function index() 
    {
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        $InsuranceTypes = InsuranceType::orderBy('status', 'desc')->latest()->get();

        return view('admin.insurance_types_and_sub_types.types', compact('sideMenuPermissions', 'sideMenuName', 'InsuranceTypes'));
    }
    
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable'
        ]);
        // dd($request);

        InsuranceType::create([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('insurance.type.index')->with(['message' => 'Insurance Type Created Successfully']);
    }
    
    public function update(Request $request, $id) 
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'nullable'
        ]);
        
        $data = InsuranceType::findOrFail($id);
        // dd($data);

        $data->update([
            'name' => $request->name,
            'status' => $request->status,
        ]);

        return redirect()->route('insurance.type.index')->with(['message' => 'Insurance Type Updated Successfully']);
    }
    public function destroy(Request $request, $id) 
    {
        try{
            InsuranceType::destroy($id);
            return redirect()->route('insurance.type.index')->with(['message' => 'Insurance Type Deleted Successfully']);
        }catch(QueryException $e){
            return redirect()->route('insurance.type.index')->with(['error' => 'This insurance type cannot be deleted because it is assigned to insurance companies.']);
        }
    }
}
