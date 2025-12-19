<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Evento;
use App\Models\Curso;
use App\Models\Categoria;
use App\Models\Participante;
use App\Models\Programa;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        // Contadores reales
        // Contadores reales
        $totalParticipants = Participante::count();
        $totalEvents = Evento::count();
        $totalCourses = Curso::count();

        // --- GESTIÓN DE OFERTAS (Req 51-57) ---
        // Filtrar programas vigentes (que no han finalizado)
        $progQuery = Programa::with(['curso', 'relator'])
            ->withCount('inscripciones')
            ->where(function ($q) {
                $q->whereDate('pro_finaliza', '>=', now())
                    ->orWhere(function ($q2) {
                        $q2->whereNull('pro_finaliza')
                            ->whereDate('pro_inicia', '>=', now());
                    });
            });

        // Actualizar contador de programas para reflejar solo los vigentes/disponibles
        $totalPrograms = $progQuery->count();

        // Filtro: Búsqueda por nombre de curso (Req 53)
        if ($request->has('search') && $request->search != '') {
            $progQuery->whereHas('curso', function ($q) use ($request) {
                $q->where('cur_nombre', 'like', '%' . $request->search . '%')
                    ->orWhere('cur_codigo', 'like', '%' . $request->search . '%');
            });
        }

        // Filtro: Modalidad (Req 57)
        if ($request->has('modalidad') && $request->modalidad != '') {
            $progQuery->whereHas('curso', function ($q) use ($request) {
                $q->where('cur_modalidad', $request->modalidad);
            });
        }

        // Ordenamiento: Próximos a iniciar primero
        $programas = $progQuery->orderBy('pro_inicia', 'asc') // Cambiado a ASC para ver los más próximos primero
            ->paginate(10, ['*'], 'programs_page');


        // Obtener eventos próximos con conteo de cursos
        $upcomingEvents = collect();
        try {
            $upcomingEvents = Evento::withCount('cursos')
                ->where('eve_estado', 1)
                ->whereNotNull('eve_inicia')
                ->where('eve_finaliza', '>=', now())
                ->orderBy('eve_inicia', 'asc')
                ->take(6)
                ->get();
        } catch (\Exception $e) {
            // Si hay error, simplemente no mostramos eventos
        }

        // Datos Reales: Áreas de Cursos (Categorías)
        $allAreas = Categoria::withCount('cursos')->get()->map(function ($cat) {
            return [
                'id' => $cat->cur_categoria,
                'name' => $cat->nom_categoria,
                'courses_count' => $cat->cursos_count,
                'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                'color' => 'blue'
            ];
        });

        // Paginación Manual para categorías
        $perPage = 4;
        $currentPage = Paginator::resolveCurrentPage() ?: 1;
        $currentItems = $allAreas->slice(($currentPage - 1) * $perPage, $perPage)->all();

        $courseAreas = new LengthAwarePaginator(
            $currentItems,
            $allAreas->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        // [CORREGIDO] Estadísticas Reales para Gráfico: Categorías más tomadas
        // Contar inscripciones reales asociadas a cursos de cada categoría
        $colors = ['blue', 'indigo', 'emerald', 'amber', 'rose', 'violet', 'cyan'];

        $topCategories = $allAreas->map(function ($cat) {
            $alumnosCount = \App\Models\Inscripcion::whereHas('curso', function ($q) use ($cat) {
                $q->where('cur_categoria', $cat['id']);
            })->count();

            $cat['alumnos'] = $alumnosCount;
            return $cat;
        })
            ->sortByDesc('alumnos')
            ->take(5)
            ->values()
            ->map(function ($cat, $index) use ($colors) {
                $cat['color'] = $colors[$index % count($colors)];
                return $cat;
            });

        // [NUEVO] Estadísticas para Gráfico: Cursos más tomados (Por cantidad de ediciones/programas)
        // Métrica: Cantidad de veces que un curso ha sido tomado (realizado/asignado) -> count(programas)
        $topCourses = Curso::withCount('programas')
            ->having('programas_count', '>', 0)
            ->orderByDesc('programas_count')
            ->take(5)
            ->get()
            ->map(function ($curso, $index) use ($colors) {
                // Invertir orden de colores o usar offset para diferenciar del otro gráfico
                $curso['color'] = $colors[($index + 2) % count($colors)]; // Offset visual
                return $curso;
            });

        return view('admin.dashboard', compact(
            'totalParticipants',
            'totalEvents',
            'totalCourses',
            'totalPrograms',
            'upcomingEvents',
            'courseAreas',
            'topCategories',
            'topCourses',
            'programas'
        ));
    }
}
