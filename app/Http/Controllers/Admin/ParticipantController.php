<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participante;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    public function index(Request $request)
    {
        $query = Participante::query();

        // Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where('par_nombre', 'like', "%{$search}%")
                  ->orWhere('par_apellido', 'like', "%{$search}%")
                  ->orWhere('par_login', 'like', "%{$search}%")
                  ->orWhere('par_correo', 'like', "%{$search}%");
        }

        // Paginación
        $participantes = $query->paginate(10)->withQueryString();

        return view('admin.participants.index', compact('participantes'));
    }
}
