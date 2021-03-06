<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLogin; //This file contains request validation and sanitization logic
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User; //Model for this controller

class LoginController extends Controller
{

    /**
     * Handles user registration. Check for any existing users and create an account
     * Redirects to dashboard after successfull account creation.
     *
     */
    public function registerEmail(UserLogin $request) {
        $credentials = $request->validated();
        if(strlen($credentials['password'])<8) {
            return redirect()->back()->withErrors('Password should be at least 8 characters');
        }
        $userexists = User::where('email', $credentials['email'])->first();
        if(!empty($userexists)) {
            return redirect()->back()->withErrors('User already exists');
        }
        $user = new User;
        $user->name = $credentials['name'];
        $user->email = $credentials['email'];
        $user->password = Hash::make($credentials['password']);
        $user->save();
        Auth::login($user);
        return redirect('fileManager');
    }

    /**
     * Handles user login. Check for credentials validity.
     * Redirects to dashboard after successfull login.
     *
     */
    public function emailLogin(UserLogin $request) {
        $request->validated();
        if (Auth::attempt($request->only('email','password'))) {
            $request->session()->regenerate();
            return redirect('fileManager');
        }
            return redirect()->back()->withErrors('Invalid Credentials');
    }

    /**
     * Handles user logout. Invalidates the session and regenerates the token
     * Redirects to login screen.
     *
     */
    public function userLogout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }

}
