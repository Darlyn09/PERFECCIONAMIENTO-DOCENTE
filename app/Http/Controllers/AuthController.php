<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'adm_login' => ['required'],
            'password' => ['required'],
        ]);

        // Auth::attempt espera 'password' como llave para la contraseña, 
        // pero el primer argumento es el campo de usuario (adm_login).
        if (Auth::attempt(['adm_login' => $request->adm_login, 'password' => $request->password])) {
            $request->session()->regenerate();

            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'adm_login' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('adm_login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
