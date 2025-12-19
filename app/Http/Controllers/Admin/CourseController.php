<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Curso::with('categoria');

        // Filtro por Categoría
        if ($request->has('categoria') && $request->categoria != '') {
            $query->where('cur_categoria', $request->categoria);
        }

        // Búsqueda simple
        if ($request->has('search') && $request->search != '') {
            $query->where('cur_nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por Fecha (Desde)
        if ($request->has('fecha_inicio') && $request->fecha_inicio != '') {
            $query->whereDate('cur_fecha_inicio', '>=', $request->fecha_inicio);
        }

        // Ordenamiento: Mas recientes (por fecha inicio) primero. Si es null, usa ID desc
        $query->orderBy('cur_fecha_inicio', 'desc')->orderBy('cur_id', 'desc');

        $courses = $query->paginate(10);
        $categorias = \App\Models\Categoria::all();

        return view('admin.courses.index', compact('courses', 'categorias'));
    }

    public function create(Request $request)
    {
        $categorias = \App\Models\Categoria::all();
        $evento = null;

        if ($request->has('evento')) {
            $evento = \App\Models\Evento::find($request->evento);
        }

        return view('admin.courses.create', compact('categorias', 'evento'));
    }

    public function store(Request $request)
    {
        // Validación simplificada
        $validated = $request->validate([
            'cur_nombre' => 'required',
            'cur_categoria' => 'required|exists:categoria,cur_categoria',
            'cur_horas' => 'required|numeric',
            'cur_asistencia' => 'required|numeric',
            'cur_descripcion' => 'nullable',
            'cur_objetivos' => 'nullable',
            'cur_contenidos' => 'nullable',
            'cur_metodologias' => 'nullable',
            'cur_bibliografia' => 'nullable',
            'cur_aprobacion' => 'nullable',
            'cur_modalidad' => 'required',
            'cur_fecha_inicio' => 'nullable|date',
            'cur_fecha_termino' => 'nullable|date|after_or_equal:cur_fecha_inicio',
            'cur_link' => 'nullable|url',
        ]);

        try {
            // Preparar datos con valores por defecto
            $data = [
                'eve_id' => $request->input('eve_id', 0),
                'cur_nombre' => $request->input('cur_nombre'),
                'cur_descripcion' => $request->input('cur_descripcion', ''),
                'cur_objetivos' => $request->input('cur_objetivos', ''),
                'cur_contenidos' => $request->input('cur_contenidos', ''),
                'cur_metodologias' => $request->input('cur_metodologias', ''),
                'cur_bibliografia' => $request->input('cur_bibliografia', ''),
                'cur_aprobacion' => $request->input('cur_aprobacion', 0),
                'cur_asistencia' => $request->input('cur_asistencia'),
                'cur_horas' => $request->input('cur_horas'),
                'cur_categoria' => $request->input('cur_categoria'),
                'cur_modalidad' => $request->input('cur_modalidad'),
                'cur_estado' => 1,
                'cur_fecha_inicio' => $request->input('cur_fecha_inicio'),
                'cur_fecha_termino' => $request->input('cur_fecha_termino'),
                'cur_link' => $request->input('cur_link', ''),
            ];

            $curso = \App\Models\Curso::create($data);

            // Si viene de un evento, redirigir al evento
            if ($request->input('eve_id') && $request->input('eve_id') > 0) {
                return redirect()->route('admin.events.show', $request->input('eve_id'))
                    ->with('success', 'Curso creado correctamente.');
            }

            return redirect()->route('admin.courses.index')->with('success', 'Curso creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el curso: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $course = \App\Models\Curso::with([
            'categoria',
            'evento',
            'programas' => function ($query) {
                $query->orderBy('pro_inicia', 'asc');
            }
        ])->findOrFail($id);

        return view('admin.courses.show', compact('course'));
    }

    public function edit($id)
    {
        $course = \App\Models\Curso::findOrFail($id);
        $categorias = \App\Models\Categoria::all();
        return view('admin.courses.edit', compact('course', 'categorias'));
    }

    public function update(Request $request, $id)
    {
        $course = \App\Models\Curso::findOrFail($id);

        $validated = $request->validate([
            'cur_nombre' => 'required',
            'cur_categoria' => 'required|exists:categoria,cur_categoria',
            'cur_horas' => 'required|numeric',
            'cur_asistencia' => 'required|numeric',
            'cur_modalidad' => 'required',
            'cur_fecha_inicio' => 'nullable|date',
            'cur_fecha_termino' => 'nullable|date|after_or_equal:cur_fecha_inicio',
        ]);

        $course->update($request->all());

        // Actualización masiva de ubicación de sesiones si se proporciona
        if ($request->has('pro_lugar_mass') && $request->filled('pro_lugar_mass')) {
            $course->programas()->update(['pro_lugar' => $request->pro_lugar_mass]);
        }

        return redirect()->route('admin.courses.index')->with('success', 'Curso actualizado correctamente.');
    }

    public function terminate($id)
    {
        $course = \App\Models\Curso::findOrFail($id);
        // Asumiendo estado 1 = Activo, 0 = Inactivo/Terminado
        $course->cur_estado = 0;
        $course->save();

        return redirect()->route('admin.courses.index')->with('success', 'Curso terminado e inhabilitado.');
    }
}
