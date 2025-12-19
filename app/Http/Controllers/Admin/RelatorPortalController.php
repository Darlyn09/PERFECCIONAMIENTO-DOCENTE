<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Informacion;
use App\Models\Relator;

class RelatorPortalController extends Controller
{
    /**
     * Muestra la lista de cursos del relator autenticado.
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || !$admin->relator) {
            abort(403, 'Acceso denegado. No se encontró un perfil de relator asociado.');
        }

        // Obtener cursos usando la relación definida en el modelo Relator con paginación
        $cursos = $admin->relator->cursos()->with('evento')->orderBy('cur_fecha_inicio', 'desc')->paginate(9);

        return view('admin.relator.my-courses', compact('cursos'));
    }

    /**
     * Muestra la lista de alumnos inscritos en un curso específico.
     */
    public function students($curso_id)
    {
        $admin = Auth::guard('admin')->user();

        // Verificar que el curso pertenece al relator
        $programas = $admin->relator->programas()->where('cur_id', $curso_id)->get();

        if ($programas->isEmpty()) {
            abort(403, 'No tiene permiso para gestionar este curso.');
        }

        // Obtener inscripciones SOLO de los programas del relator
        // Se agrupan por programa para facilitar la vista
        $programasIds = $programas->pluck('pro_id');

        $inscripciones = Inscripcion::with(['participante', 'informacion', 'programa']) // Agregamos relación programa si existe, sino crearla o usar pro_id
            ->whereIn('pro_id', $programasIds)
            ->get()
            ->groupBy('pro_id'); // Agrupar por ID de programa

        return view('admin.relator.course_students', compact('curso', 'inscripciones', 'programas'));


    }

    /**
     * Aprueba o desaprueba a un alumno (actualiza inf_estado).
     */
    public function toggleApproval(Request $request, $ins_id)
    {
        $request->validate([
            'estado' => 'required|boolean',
        ]);

        $inscripcion = Inscripcion::findOrFail($ins_id);

        // Verificar permisos (el curso debe ser del relator)
        // Esto es una validación extra de seguridad
        $admin = Auth::guard('admin')->user();
        $esCursoDelRelator = $admin->relator->cursos()->where('curso.cur_id', $inscripcion->cur_id)->exists();

        if (!$esCursoDelRelator) {
            abort(403, 'No tiene permiso para gestionar este curso.');
        }

        // Buscar o crear registro en informacion
        $informacion = Informacion::firstOrNew(['ins_id' => $ins_id]);
        $informacion->inf_estado = $request->estado ? 1 : 0;
        $informacion->save();

        return back()->with('success', 'Estado de aprobación actualizado correctamente.');
    }
}
