<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Events\UserRegistered;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:users.read')->only(['index', 'show']);
        $this->middleware('permission:users.write')->only(['edit', 'update']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select(['id', 'name', 'email', 'mobile']);
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editUrl = '';
                    $deleteUrl = route('users.destroy', $row->id);
                    $viewUrl = '';

                    return '<a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-eye ti-md"></i></a>
                            <a href="app-user-view-account.html" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill"><i class="ti ti-pencil me-1"></i></a>
                            <button type="button" class="btn btn-icon btn-text-secondary waves-effect waves-light rounded-pill delete-record" id="confirm-text" data-url="' . $deleteUrl . '" data-id="' . $row->id . '"><i class="ti ti-trash ti-md"></i></button>
                            <a href="app-user-view-account.html" class="btn btn-info waves-effect waves-light"><i class="ti ti-pencil me-1"></i>Module Permission</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|digits:10',
            'roles' => 'required',
        ]);

        $username = $request->username;
        $mobile = $request->mobile;
        $generatedPassword = $this->generatePassword($username, $mobile);

        $user = User::create([
            'name' => $username,
            'email' => $request->email,
            'mobile' => $mobile,
            'password' => bcrypt($generatedPassword),
        ]);

        $user->assignRole($request->roles);

        // event(new UserRegistered($user));

        return redirect()->route('users.index')->with('success', 'Registration successful. Please check your email for your login credentials.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user) {
            $user->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    private function generatePassword($username, $mobile)
    {
        $first4Letters = substr($username, 0, 4); // First 4 letters of username
        $last4Digits = substr($mobile, -4);      // Last 4 digits of mobile
        return strtolower($first4Letters) . $last4Digits; // Concatenate and return
    }
}
