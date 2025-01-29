<?php

namespace App\Http\Controllers\Admin;

use App\Models\Tehsil;
use App\Models\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TehsilController extends Controller
{
    public function index($id)
    {
        $district = District::find($id);
        
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
        }

        $tehsils = Tehsil::where('district_id', $id)->orderBy('name', 'asc')->get();
        return view('admin.land.tehsils.index', compact('district', 'tehsils','sideMenuName'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'district_id' => 'required',
            'name' => 'required|string|unique:tehsils,name'
        ]);
        // dd($request->district_id);

        Tehsil::create([
            'district_id' => $request->district_id,
            'name' => $request->name,
        ]);

        return redirect()->route('tehsil.index', ['id' => $request->district_id])->with(['message' => 'Tehsil Created Successfully']);

    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|unique:tehsils,name'
        ]);

        $data = Tehsil::findOrFail($id);
        $data->update([
            'name' => $request->name
        ]);

        return redirect()->route('tehsil.index', ['id' => $request->district_id])->with(['message' => 'Tehsil Updated Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        Tehsil::destroy($id);
        return redirect()->route('tehsil.index', ['id' => $request->district_id])->with(['message' => 'Tehsil Deleted Successfully']);
    }
}
