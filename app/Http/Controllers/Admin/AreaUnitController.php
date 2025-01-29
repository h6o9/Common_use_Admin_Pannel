<?php

namespace App\Http\Controllers\Admin;

use App\Models\AreaUnit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AreaUnitController extends Controller
{
    public function index() 
    {
        $units = AreaUnit::orderBy('unit', 'asc')->get();
        // dd($units);
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }
        return view('admin.land.units', compact('units','sideMenuPermissions', 'sideMenuName'));
    }

    public function store(Request $request)
    {
        // dd($request);
        // Validation
        $request->validate([
            'unit' => 'required|string|unique:area_units,unit',
        ]);

        // Create the record
        AreaUnit::create([
            'unit' => $request->unit,
        ]);

        // Redirect or return response
        return redirect()->route('units.index')->with('message', 'Unit created successfully!');
    }

    public function update(Request $request, $id) 
    {
        $request->validate([
            'unit' => 'required|string|unique:area_units,unit',
        ]);

        $data = AreaUnit::find($id);

        $data->update([
            'unit' => $request->unit,
        ]);

        return redirect()->route('units.index')->with('message', 'Unit updated successfully!');
    }

    public function destroy($id) 
    {
        AreaUnit::destroy($id);
        return redirect()->route('units.index')->with('message', 'Unit deleted successfully!');
    }
}
