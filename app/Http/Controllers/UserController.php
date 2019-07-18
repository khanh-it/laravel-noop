<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show application change password.
     *
     * @param $request Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function changePassword(Request $request)
    {
        return view('user.change-password');
    }

    /**
     * Do application change password.
     *
     * @param $request Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function doChangePassword(Request $request)
    {
        $validator = Validator::make($data = $request->all(), [
            'password_confirmation' => 'required|string|min:6',
            'password' => 'required|string|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update password
        $user = $request->user();
        $user->password = Hash::make($data['password']);
        $user->save();

        // Logout
        Auth::logout();

        // Redirect
        return redirect()->route('login');
    }
}
