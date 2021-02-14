<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserLogin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    //public $user = new User;
    public function registerGmail() {

    }


    public function registerEmail(UserLogin $request) {
        $credentials = $request->validated();
        //Log::debug('message'.print_r($credentials,true));
        if(strlen($credentials['password'])<8) {
            return redirect()->back()->withErrors('Password should be at least 8 characters');
        }
        $userexists = User::where('email', $credentials['email'])->first();
        //Log::debug('message'.$userexists);
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

    public function emailLogin(UserLogin $request)
    {
        $credentials = $request->validated();
        //Log::debug($credentials);
        if (Auth::attempt($request->only('email','password'))) {
            $request->session()->regenerate();
            $userexists = json_decode(User::where('email', $credentials['email'])->first(),true);
            //Log::debug($userexists);
            return redirect('fileManager');
        }
            return redirect()->back()->withErrors('Invalid Credentials');
    }

    public function userLogout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('login');
    }

    public function forgottenPassword(Request $request)
    {

    }
}
