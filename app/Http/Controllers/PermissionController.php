<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

    /**
     * Sync all 'admin' prefixed routes with 'auth' and 'role:Admin' middleware as permissions,
     * adding only the ones that don't exist, with readable permission names.
     * Optionally skips specific route families.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function syncAdminGroupPermissions()
    {
        // List of route families to skip (e.g., 'users', 'settings', etc.)
        $skipFamilies = ['plans', 'business-client-wallets', 'subscriptions', 'ClientStores'];

        $routes = collect(Route::getRoutes())->filter(function ($route) use ($skipFamilies) {
            // Filter routes with 'admin' prefix and the required middlewares
            $isAdminPrefix = str_starts_with($route->uri, 'admin');
            $hasAuthMiddleware = in_array('auth', $route->middleware());
            $hasRoleAdminMiddleware = in_array('role:Admin', $route->middleware());

            // Check if the route URI starts with any of the skip families
            $shouldSkip = false;
            foreach ($skipFamilies as $family) {
                if (str_starts_with($route->uri, "admin/$family")) {
                    $shouldSkip = true;
                    break;
                }
            }

            return $isAdminPrefix && $hasAuthMiddleware && $hasRoleAdminMiddleware && !$shouldSkip;
        });

        // Get existing permissions
        $existingPermissions = Permission::pluck('name')->toArray();

        $newPermissions = [];
        $routes = [
            'dashboard' => ['view'],
            'clientStores' => ['create', 'view', 'update', 'delete'],
            'api-items' => ['edit', 'import'],
            'permissions' => ['view', 'create', 'update', 'delete'],
            'purchase-requests' => ['create', 'view', 'update', 'delete'],
            'clients' => ['create', 'view', 'update', 'delete'],
            'accounts' => ['create', 'view', 'update', 'delete'],
            'user-wallets' => ['create', 'view', 'update', 'delete'],
            'users' => ['create', 'view', 'update', 'delete'],
            'sliders' => ['create', 'view', 'update', 'delete'],
            'orders' => ['create', 'view', 'update', 'delete'],
            'configs' => ['edit', 'update'],
            'terms' => ['edit', 'update'],
            'business-client-wallets' => ['create', 'view', 'update', 'delete'],
            'categories' => ['create', 'view', 'update', 'delete'],
            'diamond-rates' => ['create', 'view', 'update', 'delete'],
            'items' => ['create', 'view', 'update', 'delete'],
            'plans' => ['create', 'view', 'update', 'delete'],
            'payment-methods' => ['create', 'view', 'update', 'delete'],
            'subscriptions' => ['create', 'view', 'update', 'delete'],
            'tags' => ['create', 'view', 'update', 'delete'],
            'footer' => ['create', 'view', 'update', 'delete'],
            'fee-groups' => ['create', 'view', 'update', 'delete'],
            'posts' => ['create', 'view', 'update', 'delete'],
            'partners' => ['create', 'view', 'update', 'delete'],
            'notifications' => ['create', 'view', 'update', 'delete'],
            'currencies' => ['create', 'view', 'update', 'delete'],
            'pages' => ['create', 'view', 'update', 'delete'],
            'news' => ['edit', 'update'],
            'profile' => ['edit', 'update'],
        ];

        foreach ($routes as $resource => $actions) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$resource}";
                if (!Permission::where('name', $permissionName)->exists()) {
                    Permission::create([
                        'name' => $permissionName,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }

        // Insert new permissions
        Permission::insert($newPermissions);

        return response()->json([
            'status' => 'success',
            'message' => count($newPermissions) . ' new permissions added.',
            'new_permissions' => $newPermissions,
        ]);
    }

    /**
     * Generate a human-readable permission name from a route URI.
     *
     * @param string $uri
     * @return string
     */
    private function generateReadablePermissionName($uri)
    {
        // Remove the 'admin/' prefix
        $cleanUri = preg_replace('/^admin\//', '', $uri);

        // Replace dashes and slashes with spaces and convert to lowercase
        $readableName = str_replace(['-', '/'], ' ', $cleanUri);

        // Optionally capitalize the first letter of each word
        return ucwords($readableName);
    }



}
