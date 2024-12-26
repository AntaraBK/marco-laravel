<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{

    public function showForgetPasswordForm()
    {
        return view('admin.auth.forgetPassword');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $rules = [
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) {
                    return $query->where('user_type', 1);
                }),
            ],
        ];

        $messages = [
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'The email address does not exist in our records.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        } else {
            $token = Str::random(64);

            try {
                DB::connection('mongodb')->collection('password_reset_tokens')->where('email', $request->email)->delete();
                DB::connection('mongodb')->collection('password_reset_tokens')->insert([
                    'email' => $request->email,
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                Mail::send('admin.auth.emailForgetPassword', ['token' => $token], function ($message) use ($request) {
                    $message->to($request->email);
                    $message->subject('Reset Password');
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Great! Successfully sent to your email.',
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry! Please try again later.',
                ], 500);
            }
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        return view('admin.auth.recoverPassword', ['token' => $token]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::connection('mongodb')->collection('password_reset_tokens')
            ->where('token', $request->token)
            ->first();

        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        } else {
            $user = User::where('email', $updatePassword['email'])->where('user_type', 1)->where('is_active', 1)
                ->update(['password' => \Hash::make($request->password)]);
        }

        DB::connection('mongodb')->collection('password_reset_tokens')->where('token', $request->token)->delete();

        return redirect('/')->with('message', 'Your password has been changed!');
    }
}
