<?php

namespace App\Http\Controllers\Admin;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class ItemController extends Controller
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

        $Items = Item::orderBy('name', 'asc')->get();

        return view('admin.item.index', compact('sideMenuPermissions', 'sideMenuName', 'Items'));
    }
    
    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:items,name',
        ]);
        // dd($request);

        Item::create([
            'name' => $request->name,
        ]);

        return redirect()->route('items.index')->with(['message' => 'Item Created Successfully']);
    }
    
    public function update(Request $request, $id) 
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:255|unique:items,name',
        ]);
        
        $data = Item::findOrFail($id);
        // dd($data);

        $data->update([
            'name' => $request->name,
        ]);

        return redirect()->route('items.index')->with(['message' => 'Item Updated Successfully']);
    }
    public function destroy(Request $request, $id) 
    {
        try{
            Item::destroy($id);
            return redirect()->route('items.index')->with(['message' => 'Item Deleted Successfully']);
        }catch(QueryException $e){
            return redirect()->route('items.index')->with(['error' => 'This item cannot be deleted because it is assigned to Authorized Dealer.']);
        }
    }
}
