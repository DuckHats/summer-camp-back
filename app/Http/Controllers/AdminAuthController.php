<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin_login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (
            $request->email == env('ADMIN_USER') &&
            $request->password == env('ADMIN_PASSWORD')
        ) {
            session(['admin_authenticated' => true]);
            return redirect('/horizon');
        }

        return back()->withErrors(['email' => 'Credenciales incorrectas']);
    }

    public function logout()
    {
        session()->forget('admin_authenticated');
        return redirect('/admin/login');
    }
}
