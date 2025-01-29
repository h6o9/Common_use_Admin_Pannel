<?php

namespace App\Http\Controllers\Admin;

use App\Models\DealerItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DealerItemController extends Controller
{
    public function index($id)
    {
        $dealer_id = $id;
        $dealerItems = DealerItem::with('authorizedDealer')->where('authorized_dealer_id', $id)->latest()->get();
        
        $sideMenuName = [];
        $sideMenuPermissions = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
            $sideMenuPermissions = $subAdminData['sideMenuPermissions'];
        }

        return view('admin.authorized_dealer.items.index', compact('sideMenuPermissions', 'sideMenuName', 'dealerItems', 'dealer_id'));
    }
    public function create($id)
    {
        $dealer_id = $id;
        
        $sideMenuName = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
        }

        return view('admin.authorized_dealer.items.create', compact( 'sideMenuName', 'dealer_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'nullable|string',
            'price' => 'nullable|string',
            'dealer_id' => 'required|string',
        ]);
        // dd($request);

        // Create a new dealer record
        DealerItem::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'authorized_dealer_id' => $request->dealer_id,
            'status' => $request->status,
        ]);

        // Return success message
        return redirect()->route('dealer.item.index', ['id' => $request->dealer_id])->with(['message' => 'Item Created Successfully']);
    }

    public function edit($dealer_id, $item_id)
    {
        $item = DealerItem::find($item_id);
        
        $sideMenuName = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
        }

        return view('admin.authorized_dealer.items.edit', compact('sideMenuName', 'item', 'dealer_id'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'nullable|string',
            'price' => 'nullable|string',
        ]);
        // dd($request);

        $item = DealerItem::findOrFail($id);

        $item->update([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return redirect()->route('dealer.item.index', ['id' => $request->dealer_id])->with(['message' => 'Item Updated Successfully']);
    }

    public function destroy(Request $request, $id)
    {
        // dd($request);
        DealerItem::destroy($id);
        return redirect()->route('dealer.item.index', ['id' => $request->dealer_id])->with(['message' => 'Item Deleted Successfully']);
    }
}
