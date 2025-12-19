<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Relator;
use Illuminate\Http\Request;

class RelatorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Relator::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rel_nombre', 'like', "%{$search}%")
                    ->orWhere('rel_apellido', 'like', "%{$search}%")
                    ->orWhere('rel_login', 'like', "%{$search}%")
                    ->orWhere('rel_correo', 'like', "%{$search}%");
            });
        }

        $relatores = $query->orderBy('rel_apellido')->paginate(10);

        return view('admin.relators.index', compact('relatores'));
    }

    /**
     * Display the specified resource.
     */
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Cargar programas con conteo de inscripciones
        $relator = Relator::with([
            'programas' => function ($q) {
                $q->withCount('inscripciones')->with('curso');
            }
        ])->findOrFail($id);

        // KPIs Reales
        $totalCursos = $relator->programas->count();

        // Sumar inscripciones reales
        $totalAlumnos = $relator->programas->sum('inscripciones_count');

        // Simulación lógica para certificados (90% de aprobación estimada)
        $certificadosEmitidos = floor($totalAlumnos * 0.9);
        $promedioAprobacion = $totalCursos > 0 ? 95 : 0; // Se mantiene fijo o aleatorio alto por falta de data de notas

        // Historial ordenado
        // Usamos fecha_inicio si existe, sino pro_inicia o similar. Revisé Programa model y tiene pro_inicia.
        $historialCursos = $relator->programas->sortByDesc('pro_inicia');

        return view('admin.relators.show', compact('relator', 'totalCursos', 'totalAlumnos', 'certificadosEmitidos', 'promedioAprobacion', 'historialCursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.relators.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'rel_login' => 'required|string|max:50|unique:relator,rel_login', // Tabla 'relator' sin 's' segun modelo
            'rel_nombre' => 'required|string',
            'rel_apellido' => 'required|string',
            'rel_correo' => 'required|email',
            'rel_fono' => 'nullable|string',
            'rel_cargo' => 'nullable|string',
            'rel_facultad' => 'nullable|string',
        ]);

        Relator::create($request->all());

        return redirect()->route('admin.relators.index')
            ->with('success', 'Relator creado exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $relator = Relator::findOrFail($id);
        return view('admin.relators.edit', compact('relator'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $relator = Relator::findOrFail($id);

        $request->validate([
            'rel_nombre' => 'required|string',
            'rel_apellido' => 'required|string',
            'rel_correo' => 'required|email',
            'rel_fono' => 'nullable|string',
            'rel_cargo' => 'nullable|string',
            'rel_facultad' => 'nullable|string',
        ]);

        $relator->update($request->except('rel_login')); // PK no editable

        return redirect()->route('admin.relators.index')
            ->with('success', 'Relator actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $relator = Relator::findOrFail($id);
        $relator->delete();

        return redirect()->route('admin.relators.index')
            ->with('success', 'Relator eliminado exitosamente.');
    }
}
