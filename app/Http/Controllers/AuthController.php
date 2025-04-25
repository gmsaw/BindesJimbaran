<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::check()){
            return back();
        }

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
                $this->_logout($request);
                return back()->withErrors([
                    'email' => 'Akun anda masih menunggu persetujuan admin.',
                ]);
            } else if($userStatus == 'rejected'){
                $this->_logout($request);
                return back()->withErrors([
                    'email' => 'Akun anda telah ditolak admin.',
                ]);
            }
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'email' => 'Terjadi kesalahan, mohon periksa kembali email atau password anda',
        ])->onlyInput('email');
    }

    public function registerView()
    {
        if(Auth::check()){
            return back();
        }

        return view('pages.auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 2;
        $user->saveOrFail();

        return redirect('/')->with('success', 'Berhasil mendaftarkan akun, menunggu persetujuan admin');
    }

    public function _logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }

    public function logout(Request $request)
    {
        if(!Auth::check()){
            return redirect('/');
        }

        $this->_logout($request);

        return redirect('/');

    }
}
