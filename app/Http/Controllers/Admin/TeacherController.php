<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Relator::query();

        if ($request->has('search') && $request->get('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('rel_nombre', 'like', "%{$search}%")
                    ->orWhere('rel_apellido', 'like', "%{$search}%")
                    ->orWhere('rel_login', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo (interno/externo)
        if ($request->has('tipo') && $request->get('tipo') !== '') {
            $tipo = $request->get('tipo');
            if ($tipo == 'interno') {
                $query->whereNotNull('rel_facultad')->where('rel_facultad', '!=', '');
            } elseif ($tipo == 'externo') {
                $query->where(function ($q) {
                    $q->whereNull('rel_facultad')->orWhere('rel_facultad', '');
                });
            }
        }

        $teachers = $query->orderBy('rel_nombre')->paginate(10);

        // Totales globales del sistema (no afectados por filtros ni paginación)
        $totalRelatores = \App\Models\Relator::count();
        $totalHabilitados = \App\Models\Relator::where('rel_estado', 1)->count();
        $totalInternos = \App\Models\Relator::whereNotNull('rel_facultad')->where('rel_facultad', '!=', '')->count();
        $totalExternos = \App\Models\Relator::where(function ($q) {
            $q->whereNull('rel_facultad')->orWhere('rel_facultad', '');
        })->count();

        return view('admin.teachers.index', compact('teachers', 'totalRelatores', 'totalHabilitados', 'totalInternos', 'totalExternos'));
    }

    public function show($id)
    {
        $teacher = \App\Models\Relator::findOrFail($id);

        // Obtener todos los programas para estadísticas
        $allProgramas = \App\Models\Programa::where('rel_login', $id)->get();
        $cursosIds = $allProgramas->pluck('cur_id')->unique();
        $allCursos = \App\Models\Curso::whereIn('cur_id', $cursosIds)->get();

        // Estadísticas globales
        $totalProgramas = $allProgramas->count();
        $totalCursos = $allCursos->count();
        $totalHoras = $allCursos->sum('cur_horas');

        // Obtener los cursos paginados
        $cursos = \App\Models\Curso::with(['categoria', 'evento'])
            ->whereIn('cur_id', $cursosIds)
            ->orderBy('cur_fecha_inicio', 'desc')
            ->paginate(5, ['*'], 'cursos_page');

        // Obtener los programas paginados
        $programas = \App\Models\Programa::with(['curso.categoria', 'curso.evento'])
            ->where('rel_login', $id)
            ->orderBy('pro_inicia', 'desc')
            ->paginate(5, ['*'], 'programas_page');

        return view('admin.teachers.show', compact('teacher', 'programas', 'cursos', 'totalProgramas', 'totalCursos', 'totalHoras'));
    }

    public function create()
    {
        $facultades = \App\Models\Facultad::orderBy('facultad')->get();
        return view('admin.teachers.create', compact('facultades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rel_login' => 'required|unique:relator,rel_login|max:50',
            'rel_nombre' => 'required',
            'rel_apellido' => 'required',
            'rel_correo' => 'required|email',
            'rel_facultad' => 'required',
            'rel_fono' => 'required',
            'rel_cargo' => 'required',
        ]);

        $relator = new \App\Models\Relator($validated);
        $relator->rel_estado = 1; // Activo
        $relator->save();

        return redirect()->route('admin.teachers.index')->with('success', 'Relator registrado correctamente.');
    }

    public function edit($id)
    {
        $teacher = \App\Models\Relator::findOrFail($id);
        $facultades = \App\Models\Facultad::orderBy('facultad')->get();
        return view('admin.teachers.edit', compact('teacher', 'facultades'));
    }

    public function update(Request $request, $id)
    {
        $teacher = \App\Models\Relator::findOrFail($id);

        $validated = $request->validate([
            'rel_nombre' => 'required',
            'rel_apellido' => 'required',
            'rel_correo' => 'required|email',
            'rel_facultad' => 'required',
            'rel_fono' => 'required',
            'rel_cargo' => 'required',
        ]);

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')->with('success', 'Relator actualizado correctamente.');
    }

    public function toggleStatus($id)
    {
        $teacher = \App\Models\Relator::findOrFail($id);
        $teacher->rel_estado = $teacher->rel_estado == 1 ? 0 : 1;
        $teacher->save();

        $status = $teacher->rel_estado == 1 ? 'habilitado' : 'inhabilitado';
        return redirect()->route('admin.teachers.index')->with('success', "Relator {$status} correctamente.");
    }
}
