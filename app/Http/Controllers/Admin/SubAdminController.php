<?php

namespace App\Http\Controllers\Admin;

use App\Models\SideMenu;
use App\Models\SubAdmin;
use Illuminate\Http\Request;
use App\Models\SubAdminPermission;
use App\Mail\SubAdminLoginPassword;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;


class SubAdminController extends Controller
{
    public function index()
    {
        $subAdmins = SubAdmin::with('permissions.side_menu')->orderBy('status', 'desc')->latest()->get();
        $sideMenus = SideMenu::all();
        
        return view('admin.subadmin.index', compact('subAdmins', 'sideMenus'));
    }

    public function create()
    {
        $sideMenus = SideMenu::all();
        // return $roles;
        return view('admin.subadmin.create', compact('sideMenus'));
    }


    public function store(Request $request)
    {
        // dd($request);
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sub_admins,email',
            'phone' => 'required|unique:sub_admins,phone',
            'status' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move(public_path('admin/assets/images/users/'), $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
        } else {
            $image = 'public/admin/assets/images/avator.png';
        }

        /**generate random password */
        $password = random_int(10000000, 99999999);

        // Create a new subadmin record
        SubAdmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($password),
            'status' => $request->status,
            'image' => $image
        ]);

        $message['name'] = $request->name;
        $message['email'] = $request->email;
        $message['password'] = $password;

        Mail::to($request->email)->send(new SubAdminLoginPassword($message));

        // Return success message
        return redirect()->route('subadmin.index')->with(['message' => 'Subadmin Created Successfully']);
    }

    public function edit($id)
    {
        $subAdmin = SubAdmin::find($id);
        // return $subAdmin;

        return view('admin.subadmin.edit', compact('subAdmin'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'status' => 'required',
            'phone' => 'required',
        ]);

        $subAdmin = SubAdmin::findOrFail($id);

        $image = $subAdmin->image;

        if ($request->hasFile('image')) {
            $destination = 'public/admin/assets/images/users/' . $subAdmin->image;
            if (File::exists($destination)) {
                File::delete($destination);
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('public/admin/assets/images/users', $filename);
            $image = 'public/admin/assets/images/users/' . $filename;
            $subAdmin->image = $image;
        }

        $subAdmin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status,
            'image' => $image,
        ]);

        return redirect()->route('subadmin.index')->with('message', 'SubAdmin Updated Successfully');
    }

    public function destroy($id)
    {
        // return $id;
        SubAdmin::destroy($id);
        return redirect()->route('subadmin.index')->with(['message' => 'SubAdmin Deleted Successfully']);
    }

    public function updatePermissions(Request $request, $id)
    {
        $data = $request->validate([
            'sub_admin_id' => 'required',
            'side_menu_id' => 'nullable',
        ]);

        // dd($request);
        $permissions = [];
        if (!empty($data['side_menu_id'])) {
            foreach ($data['side_menu_id'] as $sideMenuId => $permissionArray) {
                foreach ($permissionArray as $permission) {
                    $permissions[] = [
                        'sub_admin_id' => $data['sub_admin_id'],
                        'side_menu_id' => $sideMenuId,
                        'permissions' => $permission, // Store each permission separately
                    ];
                }
            }
        }
        
        SubAdminPermission::where('sub_admin_id', $id)->delete();

        SubAdminPermission::insert($permissions);

        return redirect()->route('subadmin.index')->with('message', 'Permissions Updated Successfully');
    }
}
