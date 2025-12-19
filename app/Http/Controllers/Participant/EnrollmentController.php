<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function store(Request $request, $courseId)
    {
        $user = Auth::guard('participant')->user();
        $course = Curso::find($courseId);

        if (!$course) {
            return back()->with('error', 'Curso no encontrado.');
        }

        // Verificar si ya está inscrito
        $exists = Inscripcion::where('par_login', $user->par_login)
            ->where('cur_id', $courseId)
            ->exists();

        if ($exists) {
            return back()->with('info', 'Ya estás inscrito en este curso.');
        }

        // Validar si viene un programa seleccionado
        $request->validate([
            'program_id' => 'nullable|exists:programa,pro_id'
        ]);

        if ($request->program_id) {
            $proId = $request->program_id;
        } else {
            // Fallback: Obtener el programa más reciente/activo
            $programa = $course->programas()->orderBy('pro_inicia', 'desc')->first();
            $proId = $programa ? $programa->pro_id : 1;
        }

        // Values defaults
        $insPerfil = 'ALU'; // Alumno
        $insUdec = 0;

        try {
            Inscripcion::create([
                'par_login' => $user->par_login,
                'cur_id' => $courseId,
                'pro_id' => $proId,
                'ins_perfil' => $insPerfil,
                'ins_udec' => $insUdec,
                'ins_date' => Carbon::now()
                // Removed ins_estado
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error al inscribir: ' . $e->getMessage());
        }

        return back()->with('success', '¡Te has inscrito correctamente en el curso: ' . $course->cur_nombre . '!');
    }
}
