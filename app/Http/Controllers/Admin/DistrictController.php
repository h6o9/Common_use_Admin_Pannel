<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\District;

class DistrictController extends Controller
{
    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|unique:districts,name'
        ]);

        District::create([
            'name' => $request->name
        ]);

        return redirect()->route('land.index')->with(['message' => 'District Created Successfully']);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|string|unique:districts,name'
        ]);

        $data = District::findOrFail($id);
        $data->update([
            'name' => $request->name
        ]);

        return redirect()->route('land.index')->with(['message' => 'District Updated Successfully']);
    }

    public function destroy($id)
    {
        District::destroy($id);
        return redirect()->route('land.index')->with(['message' => 'District Deleted Successfully']);
    }
}
