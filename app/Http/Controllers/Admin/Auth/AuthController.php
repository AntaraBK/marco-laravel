<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function index(): View
    {
        return view('admin.auth.login');
    }

    public function registration(): View
    {
        return view('admin.auth.registration');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'The email is required.',
            'email.email' => 'Please provide a valid email address.',
            'password.required' => 'The password is required.'
        ]);
        if (Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'success' => true,
                'redirect_url' => route('dashboard.index'), // Change this to your intended route
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials. Please try again.',
        ], 401);
    }

    public function logout(): RedirectResponse
    {
        Session::flush();
        Auth::logout();

        return Redirect('/login');
    }

    public function showProfile(): View
    {
        $user = auth()->user();
        return view('admin.auth.profileDetails',compact('user'));
    }

    public function showChangePassword(): View
    {
        return view('admin.auth.changePassword');
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(Auth::id(), 'id')
            ],
            'mobile' => 'required|string|max:10',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:800',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->save();

        return response()->json(['success' => true, 'message' => 'Profile updated successfully']);
    }

    public function changePassword(Request $request)
    {
        $validator = [
            'currentPassword' => ['required', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'newPassword' => 'required|min:5',
        ];
        $validator = Validator::make($request->all(), $validator);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()->toArray()
            ], 422);
        }
        User::find(Auth::id())->update([
            'password' => Hash::make($request->newPassword)
        ]);

        return response()->json(['success' => 'Password changed successfully.']);
    }

    public function registrationStore(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'required|digits:10',
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

        event(new UserRegistered($user));

        return redirect()->route('login')->with('success', 'User created successful.');
    }

    private function generatePassword($username, $mobile)
    {
        $first4Letters = substr($username, 0, 4); // First 4 letters of username
        $last4Digits = substr($mobile, -4);      // Last 4 digits of mobile
        return strtolower($first4Letters) . $last4Digits; // Concatenate and return
    }
}
