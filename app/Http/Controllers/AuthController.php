<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $userStatus = Auth::user()->status;

            if ($userStatus == 'submitted'){
                return back()->withErrors([
                    'email' => 'Akun anda masih menunggu persetujuan admin',
                ]);
            } else if($userStatus == 'rejected'){
                return back()->withErrors([
                    'email' => 'Akun anda telah ditolak admin',
                ]);
            }
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'Terjadi kesalahan, mohon periksa kembali email atau password anda',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');

    }
}
