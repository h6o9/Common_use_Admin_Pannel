<?php

namespace App\Http\Controllers\Admin;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
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

        return view('admin.notification.index', compact('sideMenuPermissions', 'sideMenuName'));
    }
    public function create() 
    {
        $sideMenuName = [];

        if (Auth::guard('subadmin')->check()) {
            $getSubAdminPermissions = new AdminController();
            $subAdminData = $getSubAdminPermissions->getSubAdminPermissions();
            $sideMenuName = $subAdminData['sideMenuName'];
        }

        return view('admin.notification.create', compact('sideMenuName'));
    }
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'user_type' => 'required|in:all,farmers,authorized_dealers',
            'message' => 'required|string|max:1000',
        ]);

        // dd($request);
        $user_type = $validated['user_type'];
        $message = $validated['message'];

        Notification::create([
            'user_type' => $user_type,
            'message' => $message,
        ]);

        return redirect()->route('notification.index')->with(['message'  =>  'Notification saved successfully! It will be sent later.']);

    }

    public function destroy() {}
}
