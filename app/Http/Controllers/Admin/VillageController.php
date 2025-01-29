<?php

namespace App\Http\Controllers\Admin;

use App\Models\Uc;
use App\Models\Village;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VillageController extends Controller
{
    public function index($id)
    {
        $uc = Uc::find($id);

        $sideMenuName = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
        }

        $villages = Village::where('uc_id', $id)->orderBy('name', 'asc')->get();
        return view('admin.land.village.index', compact('uc', 'villages','sideMenuName'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'uc_id' => 'required',
            'name' => 'required|string|unique:villages,name'
        ]);
        // dd($request);

        Village::create([
            'uc_id' => $request->uc_id,
            'name' => $request->name,
        ]);

        return redirect()->route('village.index', ['id' => $request->uc_id])->with(['message' => 'Village Created Successfully']);

    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|unique:villages,name'
        ]);

        $data = Village::findOrFail($id);
        $data->update([
            'name' => $request->name
        ]);

        return redirect()->route('village.index', ['id' => $request->uc_id])->with(['message' => 'Village Updated Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        Village::destroy($id);
        return redirect()->route('village.index', ['id' => $request->uc_id])->with(['message' => 'Village Deleted Successfully']);
    }
}
