<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    public function show($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.permissions.show', compact('role', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();

        return view('admin.permissions.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
        $role->syncPermissions($permissions);

        return redirect()->route('permissions.index')->with('success', 'Role created successfully.');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('admin.permissions.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        // Fetch permissions by their IDs
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        // Sync permissions with the role
        $role->syncPermissions($permissions);

        return redirect()->route('permissions.index')->with('success', 'Role updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(['status' => 'success', 'message' => 'Role deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'An error occurred while deleting the role.']);
        }
    }

    public function assignPermissionsForm()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.permissions.assign', compact('roles', 'permissions'));
    }

    public function assignPermissions(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::findById($request->role);
        $role->syncPermissions($request->permissions);

        return redirect()->back()->with('success', 'Permissions assigned successfully.');
    }
}
