<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
    
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            Session::put('username', $request->username);
            
            // Debugging: Cek apakah user sudah login
            
            return redirect()->intended('dashboard');
        }                
    
        return back()->withErrors(['login' => 'Username atau password salah.']);
    }
        
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::forget('username');
        
        return redirect()->route('login');
    }
}