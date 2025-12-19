<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Evento;
use App\Models\Matricula;

class DashboardController extends Controller
{
    public function index()
    {
        $participante = Auth::guard('participant')->user();

        // 0. IDs de cursos inscritos (para excluir de disponibles)
        $enrolledCourseIds = $participante->cursos->pluck('cur_id')->toArray();

        // Obtener cursos disponibles (excluyendo los ya inscritos)
        $availableCourses = Curso::with([
            'programas' => function ($q) {
                $q->whereDate('pro_inicia', '>=', now())
                    ->orderBy('pro_inicia', 'asc');
            }

        ])
            ->where('cur_estado', 1)
            ->whereNotIn('cur_id', $enrolledCourseIds) // Excluir inscritos
            ->whereDate('cur_fecha_inicio', '>=', now())
            ->whereHas('programas', function ($q) {
                $q->whereDate('pro_inicia', '>=', now());
            })
            ->orderBy('cur_fecha_inicio', 'asc')
            ->take(6)
            ->get();

        // Obtener eventos
        // Obtener eventos próximos
        // Obtener eventos próximos (incluyendo los que están en curso)
        $events = Evento::where('eve_estado', 1)
            ->where('eve_finaliza', '>=', now()) // Que no hayan terminado aún
            ->orderBy('eve_inicia', 'asc')
            ->take(3)
            ->get();

        // Obtener evento en curso (lo que está pasando AHORA)
        $currentEvent = Evento::where('eve_estado', 1)
            ->where(function ($q) {
                $q->where('eve_inicia', '<=', now())
                    ->where('eve_finaliza', '>=', now());
            })
            ->first();

        // Si hay evento en curso, lo agregamos al principio de la lista o lo pasamos separado
        // Para simplificar la vista, lo pasamos separado.

        // Estadísticas para el Carrusel
        // Estadísticas para el Carrusel
        // 1. Cursos Aprobados vs Totales
        // Usamos la relación 'inscripciones' para obtener datos más fiables de 'informacion' y 'curso'
        $enrollments = $participante->inscripciones()->with(['curso', 'informacion'])->get();

        $totalCourses = $enrollments->count();

        $approvedCourses = $enrollments->filter(function ($enrollment) {
            // Priority: Check Direct Approval Status
            if ($enrollment->informacion && $enrollment->informacion->inf_estado == 1) {
                return true;
            }
            // REMOVED LEGACY FALLBACK:
            // Previously we counted expired courses as approved. This caused discrepancies.
            // Now we strictly count only explicitly approved courses.
            return false;
        })->count();

        // 2. Horas Totales
        // Sumamos las horas de los cursos inscritos
        $totalHours = $enrollments->sum(function ($enrollment) {
            return $enrollment->curso ? $enrollment->curso->cur_horas : 0;
        });

        // 3. Categorías más frecuentes
        $categories = $enrollments->groupBy(function ($enrollment) {
            return $enrollment->curso ? $enrollment->curso->cur_categoria : 'General';
        })->map->count()->sortDesc()->take(3);
        // Mapear IDs a nombres si es necesario, o si cur_categoria es string, directo.
        // Asumimos que cur_categoria es ID.
        // Simularemos datos para el gráfico si no hay suficientes.

        // EXTRA: Último Curso Aprobado (para destacar en sidebar)
        // Filtramos los aprobados y ordenamos por fecha de término del curso descendente
        $lastApprovedCourse = $enrollments->filter(function ($enrollment) {
            return $enrollment->informacion && $enrollment->informacion->inf_estado == 1 && $enrollment->curso;
        })->sortByDesc(function ($enrollment) {
            return $enrollment->curso->cur_fecha_termino ?? '1900-01-01';
        })->first(); // Tomamos la inscripción, luego en la vista accedemos a ->curso

        $stats = [
            'total_courses' => $totalCourses,
            'approved_courses' => $approvedCourses,
            'total_hours' => $totalHours,
            'categories' => $categories
        ];

        // IDs de cursos inscritos ya obtenidos arriba

        return view('participant.dashboard', compact('participante', 'availableCourses', 'events', 'stats', 'enrolledCourseIds', 'currentEvent', 'lastApprovedCourse'));
    }

    public function myCourses()
    {
        $participante = Auth::guard('participant')->user();

        // Use inscripciones to get robust data
        // We select key relations
        $enrollments = $participante->inscripciones()
            ->with(['curso.programas', 'curso.categoria', 'informacion'])
            ->get();

        // Map enrollments to courses collection, hydrating properties
        $enrolledCourses = $enrollments->map(function ($enrollment) {
            $course = $enrollment->curso;
            if (!$course)
                return null;

            // Hydrate approval
            // Check pivot info first
            $isApproved = false;
            if ($enrollment->informacion && $enrollment->informacion->inf_estado == 1) {
                $isApproved = true;
            }
            $course->is_approved = $isApproved;

            // Hydrate Pivot ins_id if needed by view (though we used it for logic mainly)
            // If view uses pivot dates or something, we might need to simulate pivot or attach it
            // The original code used ->withPivot('ins_id'). 
            // We can manually set it if needed, but view mostly uses course attributes.

            return $course;
        })->filter()->values(); // Remove nulls and reindex

        // Determine sorting manually since we lost database ordering
        $enrolledCourses = $enrolledCourses->sortByDesc('cur_fecha_inicio');

        // Fetch user events
        $myEvents = $participante->eventos()->orderBy('eve_inicia', 'desc')->get();

        return view('participant.my-courses', compact('participante', 'enrolledCourses', 'myEvents'));
    }

    public function agenda()
    {
        $participante = Auth::guard('participant')->user();
        $now = now();

        // 1. En curso (Inscrito y activo)
        // Usamos inscripciones para verificar 'informacion' (aprobación) correctamente
        $activeEnrollments = $participante->inscripciones()
            ->with(['curso.programas', 'informacion'])
            ->whereHas('curso', function ($q) use ($now) {
                $q->where('cur_estado', 1)
                    ->where('cur_fecha_inicio', '<=', $now)
                    ->where('cur_fecha_termino', '>=', $now);
            })
            ->get();

        $activeCourses = $activeEnrollments->filter(function ($ins) {
            // Excluir si ya está aprobado
            if ($ins->informacion && $ins->informacion->inf_estado == 1) {
                return false;
            }
            return true;
        })->map(function ($ins) {
            return $ins->curso;
        });

        $activeEvents = $participante->eventos()
            ->where('eve_estado', 1)
            ->where('eve_inicia', '<=', $now)
            ->where('eve_finaliza', '>=', $now)
            ->get();

        // 2. Próximos (Todos los disponibles o inscritos futuros)
        // Eventos próximos (todos los públicos)
        $upcomingEvents = Evento::where('eve_estado', 1)
            ->whereDate('eve_inicia', '>=', $now)
            ->orderBy('eve_inicia', 'asc')
            ->take(5)
            ->get();

        // Cursos próximos disponibles (no inscritos)
        $enrolledIds = $participante->cursos->pluck('cur_id')->toArray();
        $upcomingCourses = Curso::where('cur_estado', 1)
            ->whereNotIn('cur_id', $enrolledIds)
            ->whereDate('cur_fecha_inicio', '>=', $now)
            ->orderBy('cur_fecha_inicio', 'asc')
            ->take(5)
            ->get();

        return view('participant.agenda', compact('activeCourses', 'activeEvents', 'upcomingEvents', 'upcomingCourses'));
    }
}
