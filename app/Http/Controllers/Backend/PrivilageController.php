<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Privilage;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RealRashid\SweetAlert\Facades\Alert;

class PrivilageController extends Controller
{
    public function index()
    {
        $privilage = Privilage::with('user', 'role')
            ->where('role_id', '!=', 1)
            ->get();
        $data = array(
            'title' => 'Privilage | ',
            'dataprivilage' => $privilage,
        );
        $title = 'Delete Privilage!';
        $text = "Are you sure you want to delete?";
        confirmDelete($title, $text);
        return view('backend.privilage.index', $data);
    }

    public function show($id)
    {
        $privilageItem = Privilage::with('user', 'role')->findOrFail($id);
        return response()->json($privilageItem);
    }

    public function create()
    {
        $excludedUserIds = Privilage::pluck('user_id')->toArray();

        $user = User::where('id', '!=', 1)
            ->whereNotIn('id', $excludedUserIds)
            ->get();
        $role = Role::where('kode_role', '!=', 'superadmin')->get();

        $data = array(
            'title' => 'Add Privilage | ',
            'datauser' => $user,
            'datarole' => $role,
        );
        return view('backend.privilage.create', $data);
    }

    public function store(Request $request)
    {
        Privilage::create($request->all());

        Alert::success('Success', 'privilage created successfully.');

        return redirect()->route('privilage.index');
    }

    public function edit(Privilage $privilage)
    {
        $user = User::where('id', '!=', 1)->get();
        $role = Role::where('kode_role', '!=', 'superadmin')->get();
        $data = array(
            'title' => 'Edit Privilage | ',
            'privilage' => $privilage,
            'datauser' => $user,
            'datarole' => $role,
        );
        return view('backend.privilage.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $privilage = Privilage::findOrFail($id);
            $privilage->update($request->all());
            Alert::success('Success', 'privilage updated successfully.');

            return redirect()->route('privilage.index');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found'], 404);
        }
    }

    public function destroy($id)
    {
        $privilage = Privilage::findOrFail($id);
        $privilage->delete();
        Alert::success('Success', 'privilage deleted successfully.');

        return redirect()->route('privilage.index');
    }

    public function getRoleName()
    {
        $roleName = Privilage::getRoleNameForAuthenticatedUser();
        return response()->json(['role_name' => $roleName]);
    }
}
