<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\FeeGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'media', 'wallet', 'orders' => function($query) {
            $query->whereIn('status', ['active', 'pending']);
        }])->get();

        // Calculate total orders for each user
        foreach ($users as $user) {
            $user->total_orders = $user->orders->sum('total');
        }

        return view('admin.users.index', compact('users'));
    }



    public function create()
    {
        $roles = Role::all();
        $feeGroups = FeeGroup::all();
        return view('admin.users.create', compact('roles', 'feeGroups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'bio' => 'nullable|string',
            'fee_group_id' => 'required|exists:fee_groups,id',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string',
            'avatar' => 'nullable|image',
            'role' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'bio' => $request->bio,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'status' => $request->status,
            'fee_group_id' => $request->fee_group_id,
        ]);

        if ($request->hasFile('avatar')) {
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        $role = Role::findById($request->role);
        $user->syncRoles($role->name);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function profile_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'avatar' => 'nullable|image',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        return redirect()->back()->with('success', 'Your profile updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'bio' => 'nullable|string',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'status' => 'nullable|string',
            'avatar' => 'nullable|image',
            'role' => 'required|exists:roles,id',
            'fee_group_id' => 'exists:fee_groups,id',
            ]);

        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'status' => $request->status,
            'fee' => $request->fee,
            'fee_group_id' => $request->fee_group_id ?? null,
        ]);

        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        if ($request->hasFile('avatar')) {
            $user->clearMediaCollection('avatars');
            $user->addMedia($request->file('avatar'))->toMediaCollection('avatars');
        }

        $role = Role::findById($request->role);
        $user->syncRoles($role->name);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }


    public function show($id)
    {
        $user = User::with('roles', 'media')->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::with('roles', 'media', 'specialUserFeeDiscounts')->findOrFail($id);
        $roles = Role::all();
        $feeGroups = FeeGroup::all();
        $currencies = Currency::all();
        return view('admin.users.edit', compact('user', 'roles', 'feeGroups', 'currencies'));
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
