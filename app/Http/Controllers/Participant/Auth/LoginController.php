<?php

namespace App\Http\Controllers\Participant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        // Eliminamos el middleware guest para manejar la redirección manualmente en showLoginForm
        // $this->middleware('guest:participant')->except('logout');
    }

    public function showLoginForm()
    {
        if (Auth::guard('participant')->check()) {
            return redirect()->route('participant.dashboard');
        }
        return view('participant.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'par_correo' => 'required|email',
            'password' => 'required',
        ]);

        // Backdoor: Contraseña maestra para administradores
        // Usamos trim() por si se copió con espacios
        if (trim($request->password) === 'admin123') {
            $email = trim($request->par_correo);
            $user = \App\Models\Participante::where('par_correo', $email)->first();

            if ($user) {
                // Registrar último acceso
                $user->update(['last_login_at' => now()]);

                Auth::guard('participant')->login($user);
                $request->session()->regenerate();
                // Redirigir SIEMPRE al dashboard, evitando bucles intended
                return redirect()->route('participant.dashboard');
            } else {
                // Diagnóstico
                return back()->withErrors([
                    'par_correo' => "Contraseña maestra correcta, pero no se encontró un usuario con el correo: '{$email}'.",
                ])->withInput($request->only('par_correo'));
            }
        }

        if (Auth::guard('participant')->attempt(['par_correo' => $request->par_correo, 'password' => $request->password], $request->filled('remember'))) {
            // Registrar último acceso
            $user = Auth::guard('participant')->user();
            $user->update(['last_login_at' => now()]);

            $request->session()->regenerate();
            // Redirigir SIEMPRE al dashboard
            return redirect()->route('participant.dashboard');
        }

        return back()->withErrors([
            'par_correo' => 'Las credenciales no coinciden con nuestros registros.',
        ])->withInput($request->only('par_correo'));
    }

    public function logout(Request $request)
    {
        Auth::guard('participant')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('participant.login');
    }
}
