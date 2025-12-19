<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Curso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Listado de eventos
     */
    public function index(Request $request)
    {
        $query = Evento::withCount(['cursos', 'cursosActivos']);
        $now = Carbon::now();

        // Búsqueda
        if ($request->has('search') && $request->search != '') {
            $query->where('eve_nombre', 'like', '%' . $request->search . '%');
        }

        // Filtro por estado
        if ($request->has('estado') && $request->estado != '') {
            $query->where('eve_estado', $request->estado);
        }

        // Filtro por período temporal
        if ($request->has('periodo') && $request->periodo != '') {
            switch ($request->periodo) {
                case 'vigente':
                    // Eventos que están en curso (fecha inicio <= hoy <= fecha fin)
                    $query->where('eve_inicia', '<=', $now)
                        ->where(function ($q) use ($now) {
                            $q->where('eve_finaliza', '>=', $now)
                                ->orWhereNull('eve_finaliza');
                        });
                    break;
                case 'proximo':
                    // Eventos que aún no comienzan
                    $query->where('eve_inicia', '>', $now);
                    break;
                case 'finalizado':
                    // Eventos que ya terminaron
                    $query->where('eve_finaliza', '<', $now);
                    break;
            }
        }

        // Filtro por mes/año
        if ($request->has('mes') && $request->mes != '') {
            $mes = $request->mes; // formato: 2025-01
            $query->where(function ($q) use ($mes) {
                $q->whereRaw("DATE_FORMAT(eve_inicia, '%Y-%m') = ?", [$mes])
                    ->orWhereRaw("DATE_FORMAT(eve_finaliza, '%Y-%m') = ?", [$mes]);
            });
        }

        // Filtro por año
        if ($request->has('anio') && $request->anio != '') {
            $query->whereYear('eve_inicia', $request->anio);
        }

        // Filtro por tipo
        if ($request->has('tipo') && $request->tipo != '') {
            $query->where('eve_tipo', $request->tipo);
        }

        // Ordenamiento
        $sort = $request->input('orden', 'fecha_desc');
        switch ($sort) {
            case 'nombre_asc':
                $query->orderBy('eve_nombre', 'asc');
                break;
            case 'nombre_desc':
                $query->orderBy('eve_nombre', 'desc');
                break;
            case 'fecha_asc':
                $query->orderBy('eve_inicia', 'asc');
                break;
            case 'fecha_desc':
            default:
                $query->orderBy('eve_inicia', 'desc');
                break;
        }

        $events = $query->paginate(12);

        // Contadores globales
        $totalEventos = Evento::count();
        $totalActivos = Evento::where('eve_estado', 1)->count();
        $totalInactivos = Evento::where('eve_estado', 0)->count();

        // Contadores por período
        $vigentes = Evento::where('eve_estado', 1)
            ->where('eve_inicia', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->where('eve_finaliza', '>=', $now)
                    ->orWhereNull('eve_finaliza');
            })->count();

        $proximos = Evento::where('eve_estado', 1)
            ->where('eve_inicia', '>', $now)->count();

        $finalizados = Evento::where('eve_finaliza', '<', $now)->count();

        // Obtener años disponibles para el filtro
        $aniosDisponibles = Evento::selectRaw('YEAR(eve_inicia) as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio')
            ->filter();

        // Obtener meses del año actual/seleccionado
        $anioSeleccionado = $request->anio ?? $now->year;

        return view('admin.events.index', compact(
            'events',
            'totalEventos',
            'totalActivos',
            'totalInactivos',
            'vigentes',
            'proximos',
            'finalizados',
            'aniosDisponibles',
            'anioSeleccionado'
        ));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Guardar nuevo evento
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'eve_nombre' => 'required|max:255',
            'eve_descripcion' => 'nullable',
            'eve_inicia' => 'required|date',
            'eve_finaliza' => 'nullable|date|after_or_equal:eve_inicia',
            'eve_tipo' => 'nullable',
        ]);

        try {
            $data = [
                'eve_nombre' => $request->input('eve_nombre'),
                'eve_descripcion' => $request->input('eve_descripcion') ?? '',
                'eve_inicia' => $request->input('eve_inicia'),
                'eve_finaliza' => $request->input('eve_finaliza'),
                'eve_abre' => $request->input('eve_abre'),
                'eve_cierra' => $request->input('eve_cierra'),
                'eve_tipo' => $request->input('eve_tipo') ?? 1,
                'eve_estado' => 1,
                'eve_imagen' => '',
            ];

            Evento::create($data);

            return redirect()->route('admin.events.index')->with('success', 'Evento creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el evento: ' . $e->getMessage());
        }
    }

    /**
     * Ver detalle del evento con sus cursos
     */
    public function show($id)
    {
        $event = Evento::with([
            'cursos' => function ($query) {
                $query->with('categoria')->orderBy('cur_fecha_inicio', 'asc');
            }
        ])->findOrFail($id);

        return view('admin.events.show', compact('event'));
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $event = Evento::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Actualizar evento
     */
    public function update(Request $request, $id)
    {
        $event = Evento::findOrFail($id);

        $validated = $request->validate([
            'eve_nombre' => 'required|max:255',
            'eve_descripcion' => 'nullable',
            'eve_inicia' => 'required|date',
            'eve_finaliza' => 'nullable|date|after_or_equal:eve_inicia',
            'eve_tipo' => 'nullable',
            'eve_estado' => 'required|in:0,1',
        ]);

        try {
            $event->update($request->only([
                'eve_nombre',
                'eve_descripcion',
                'eve_inicia',
                'eve_finaliza',
                'eve_tipo',
                'eve_estado',
                'eve_abre',
                'eve_cierra'
            ]));

            return redirect()->route('admin.events.show', $id)->with('success', 'Evento actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el evento: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado del evento
     */
    /**
     * Cambiar estado del evento
     */
    public function toggleStatus($id)
    {
        $event = Evento::findOrFail($id);
        $event->eve_estado = $event->eve_estado == 1 ? 0 : 1;
        $event->save();

        $status = $event->eve_estado == 1 ? 'activado' : 'desactivado';
        return redirect()->back()->with('success', "Evento {$status} correctamente.");
    }

    /**
     * Eliminar evento
     */
    public function destroy($id)
    {
        $event = Evento::findOrFail($id);

        try {
            // Verificar si tiene cursos activos antes de eliminar? 
            // Por ahora eliminamos el evento (cascada si está configurado, o soft delete si lo hubiera)
            // Asumimos eliminación directa
            $event->delete();
            return redirect()->route('admin.events.index')->with('success', 'Evento eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al eliminar el evento: ' . $e->getMessage());
        }
    }
}
