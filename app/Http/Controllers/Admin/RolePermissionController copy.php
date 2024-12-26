<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;


class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $roles = Role::with('permissions')->get();
        if ($request->ajax()) {
            $roles = Role::with('permissions');
            return DataTables::of($roles)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = '';
                    $deleteUrl = route('users.destroy', $row->id);
                    $viewUrl = '';

                    return '<a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>
                            <a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-pencil me-1"></i></a>
                            <button type="button" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" id="confirm-text" data-url="' . $deleteUrl . '" data-id="' . $row->id . '"><i class="ti ti-trash ti-md"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.roles.index', compact('roles'));
    }

    // Store a new role
    public function storeRole(Request $request)
    {
        $request->validate(['name' => 'required|unique:roles']);
        Role::create(['name' => $request->name]);
        return back()->with('success', 'Role created successfully.');
    }

    // List all modules
    public function listModules()
    {
        $modules = Module::with('permissions')->get();
        return view('modules.index', compact('modules'));
    }

    // Store a new module
    public function storeModule(Request $request)
    {
        $request->validate(['name' => 'required|unique:modules']);
        Module::create(['name' => $request->name]);
        return back()->with('success', 'Module created successfully.');
    }

    // Store permissions for a module
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'module_id' => 'required|exists:modules,id',
        ]);

        Permission::create([
            'name' => $request->name,
            'module_id' => $request->module_id,
        ]);

        return back()->with('success', 'Permission added successfully.');
    }
}
