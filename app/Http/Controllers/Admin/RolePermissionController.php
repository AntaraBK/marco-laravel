<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::with('permissions');
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = '';
                    $deleteUrl = route('roles.destroy', $row->id);
                    $viewUrl = '';

                    return '<button data-bs-target="#editRoleModal" data-bs-toggle="modal"
                                class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill edit-role-btn" data-id="' . $row->id . '">
                               <i class="ti ti-pencil me-1"></i>
                            </button>
                            <button type="button" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" id="confirm-text" data-url="' . $deleteUrl . '" data-id="' . $row->id . '"><i class="ti ti-trash ti-md"></i></button>';
                            // <a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $permissions = Permission::all();
        // dd($permissions);
        return view('admin.role.roles.index', compact('permissions'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array|min:1',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
        ]);
        $role->givePermissionTo($validated['permissions']);
        return response()->json([
            'success' => 'true',
            'message' => 'Role and permissions created successfully!'
        ]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $groupedPermissions = [];
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission->name);
            $group = $parts[0];
            $action = $parts[1];

            if (!isset($groupedPermissions[$group])) {
                $groupedPermissions[$group] = [];
            }
            $groupedPermissions[$group][] = $action;
        }
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return response()->json([
            'success' => true,
            'role' => $role,
            'permissions' => $groupedPermissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions' => 'required|array|min:1',
        ]);
        $role = Role::findOrFail($id);
        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return response()->json([
            'success' => true,
            'message' => 'Role and permissions updated successfully!',
        ]);
    }

    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Check if role is being used by any users
            if ($role->users()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This role cannot be deleted as it is assigned to users.'
                ], 422);
            }

            // Remove all permissions before deleting the role
            $role->syncPermissions([]);
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting role: ' . $e->getMessage()
            ], 500);
        }
    }
}
