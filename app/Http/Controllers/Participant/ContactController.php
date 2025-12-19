<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function show()
    {
        $user = Auth::guard('participant')->user();
        return view('participant.contact', compact('user'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ]);

        // Aquí se implementaría el envío real de correo o guardado en BD.
        // Por ahora simulamos éxito.

        return back()->with('success', 'Tu mensaje ha sido enviado correctamente. Nos pondremos en contacto contigo pronto.');
    }
}
