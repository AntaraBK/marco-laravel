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
                    $deleteUrl = route('users.destroy', $row->id);
                    $viewUrl = '';

                    return '<a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>
                            <a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-pencil me-1"></i></a>
                            <button type="button" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" id="confirm-text" data-url="' . $deleteUrl . '" data-id="' . $row->id . '"><i class="ti ti-trash ti-md"></i></button>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $permissions = Permission::all();
        // dd($permissions);
        return view('admin.role.roles.index', compact('permissions'));
    }

    


}
