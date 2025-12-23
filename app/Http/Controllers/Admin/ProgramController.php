<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Models\Curso;
use App\Models\Relator;
use App\Models\Participante;
use App\Imports\TeachersImport;
use App\Imports\GradesImport;
use App\Imports\ParticipantsImport;
use App\Models\Inscripcion;
use App\Models\Informacion;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Listado Global de Ofertas (Req 1)
     */
    public function indexGlobal(Request $request)
    {
        $query = Programa::with(['curso.evento', 'relator', 'relatores'])
            ->withCount('inscripciones');

        // Búsqueda por nombre de curso
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('curso', function ($q) use ($search) {
                $q->where('cur_nombre', 'like', '%' . $search . '%');
            });
        }

        // Filtro por Modalidad
        if ($request->has('modalidad') && $request->modalidad != '') {
            $modalidad = $request->modalidad;
            $query->whereHas('curso', function ($q) use ($modalidad) {
                $q->where('cur_modalidad', $modalidad);
            });
        }

        $programas = $query->orderBy('pro_inicia', 'desc')->paginate(10);

        return view('admin.offerings.index', compact('programas'));
    }

    /**
     * Formulario de creación de programa
     */
    public function create(Request $request)
    {
        $curso = Curso::with('evento')->findOrFail($request->curso);
        $relatores = Relator::where('rel_estado', 1)->get();
        // Obtener participantes para selección de colaboradores (nombre y apellido)
        $participantes = Participante::select('par_login', 'par_nombre', 'par_apellido')
            ->orderBy('par_nombre')
            ->get();

        return view('admin.programs.create', compact('curso', 'relatores', 'participantes'));
    }

    /**
     * Guardar nuevo programa
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'cur_id' => 'required|exists:curso,cur_id',
            'pro_inicia' => 'required|date',
            'pro_finaliza' => 'nullable|date|after_or_equal:pro_inicia',
            'pro_hora_inicio' => 'nullable',
            'pro_hora_termino' => 'nullable',
            'pro_lugar' => 'nullable|max:255',
            'pro_cupos' => 'nullable|numeric|min:1',
            'rel_login' => 'nullable',
            'relatores' => 'nullable|array',
            'relatores.*' => 'exists:relator,rel_login'
        ]);

        try {
            $data = [
                'cur_id' => $request->input('cur_id'),
                'rel_login' => $request->input('rel_login') ?? '',
                'pro_colaboradores' => $request->input('pro_colaboradores') ?? '',
                'pro_inicia' => $request->input('pro_inicia'),
                'pro_finaliza' => $request->input('pro_finaliza'),
                'pro_abre' => $request->input('pro_abre'),
                'pro_cierra' => $request->input('pro_cierra'),
                'pro_horario' => $request->input('pro_horario') ?? '',
                'pro_lugar' => $request->input('pro_lugar') ?? '',
                'pro_cupos' => $request->input('pro_cupos') ?? 0,
                'pro_hora_inicio' => $request->input('pro_hora_inicio') ?? '',
                'pro_hora_termino' => $request->input('pro_hora_termino') ?? '',
            ];

            $programa = Programa::create($data);

            if ($request->has('relatores')) {
                $programa->relatores()->sync($request->input('relatores', []));
            }

            return redirect()->route('admin.courses.show', $request->input('cur_id'))
                ->with('success', 'Programa creado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el programa: ' . $e->getMessage());
        }
    }

    /**
     * Formulario de edición
     */
    public function edit($id)
    {
        $programa = Programa::with(['curso', 'relatores'])->findOrFail($id);
        $relatores = Relator::where('rel_estado', 1)->get();
        // Obtener participantes para selección de colaboradores
        $participantes = Participante::select('par_login', 'par_nombre', 'par_apellido')
            ->orderBy('par_nombre')
            ->get();

        return view('admin.programs.edit', compact('programa', 'relatores', 'participantes'));
    }

    /**
     * Actualizar programa
     */
    public function update(Request $request, $id)
    {
        $programa = Programa::findOrFail($id);

        $validated = $request->validate([
            'pro_inicia' => 'required|date',
            'pro_finaliza' => 'nullable|date|after_or_equal:pro_inicia',
            'pro_hora_inicio' => 'nullable',
            'pro_hora_termino' => 'nullable',
            'pro_lugar' => 'nullable|max:255',
            'pro_cupos' => 'nullable|numeric|min:1',
            'rel_login' => 'nullable',
            'relatores' => 'nullable|array',
            'relatores.*' => 'exists:relator,rel_login'
        ]);

        try {
            $programa->update([
                'rel_login' => $request->input('rel_login') ?? '',
                'pro_colaboradores' => $request->input('pro_colaboradores') ?? '',
                'pro_inicia' => $request->input('pro_inicia'),
                'pro_finaliza' => $request->input('pro_finaliza'),
                'pro_abre' => $request->input('pro_abre'),
                'pro_cierra' => $request->input('pro_cierra'),
                'pro_horario' => $request->input('pro_horario') ?? '',
                'pro_lugar' => $request->input('pro_lugar') ?? '',
                'pro_cupos' => $request->input('pro_cupos') ?? 0,
                'pro_hora_inicio' => $request->input('pro_hora_inicio') ?? '',
                'pro_hora_termino' => $request->input('pro_hora_termino') ?? '',
            ]);

            if ($request->has('relatores')) {
                $programa->relatores()->sync($request->input('relatores', []));
            }

            return redirect()->route('admin.courses.show', $programa->cur_id)
                ->with('success', 'Programa actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el programa: ' . $e->getMessage());
        }
    }




    public function destroy($id)
    {
        $programa = Programa::findOrFail($id);
        $cursoId = $programa->cur_id;

        try {
            $programa->delete();
            return redirect()->route('admin.courses.show', $cursoId)
                ->with('success', 'Programa eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el programa: ' . $e->getMessage());
        }
    }

    /**
     * Exportar participantes a CSV (Req 58)
     */
    /**
     * Gestión de Docentes (Req 59, 60, 61, 62)
     */
    public function teachers($id)
    {
        $programa = Programa::with(['curso', 'relatores'])->findOrFail($id);
        // Asegurar que el Relator Principal esté en la tabla pivot para poder certificarlo
        if ($programa->rel_login && !$programa->relatores->contains('rel_login', $programa->rel_login)) {
            try {
                $programa->relatores()->attach($programa->rel_login, [
                    'rr_nota' => null,
                    'rr_asistencia' => null,
                    'rr_certificado' => null
                ]);
                $programa->load('relatores'); // Recargar relación
            } catch (\Exception $e) {
                // Ignorar si ya existe (race condition) o error de clave foránea
            }
        }

        // Excluir relatores ya asignados (para el dropdown de agregar otro)
        $excludeIds = $programa->relatores->pluck('rel_login')->toArray();
        $relatoresDisponibles = Relator::where('rel_estado', 1)
            ->whereNotIn('rel_login', $excludeIds)
            ->orderBy('rel_nombre')
            ->get();

        return view('admin.programs.teachers', compact('programa', 'relatoresDisponibles'));
    }

    public function assignTeacher(Request $request, $id)
    {
        $request->validate([
            'rel_login' => 'required|exists:relator,rel_login'
        ]);

        $programa = Programa::findOrFail($id);

        // Evitar duplicados
        if (!$programa->relatores()->where('relator.rel_login', $request->rel_login)->exists()) {
            $programa->relatores()->attach($request->rel_login);
            return back()->with('success', 'Docente asignado correctamente.');
        }

        return back()->with('warning', 'El docente ya está asignado a este programa.');
    }

    public function detachTeacher($id, $relLogin)
    {
        $programa = Programa::findOrFail($id);
        $programa->relatores()->detach($relLogin);

        return back()->with('success', 'Docente eliminado del programa.');
    }

    public function importTeachers(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $programa = Programa::findOrFail($id);

        try {
            $import = new TeachersImport($programa->pro_id); // Pass program ID
            Excel::import($import, $request->file('file'));

            return back()->with('success', "Carga masiva completada. Se han procesado los registros correctamente. Asignados: {$import->importedCount}. Fallidos: {$import->failedCount}.");
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    public function importParticipants(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls'
        ]);

        $programa = Programa::findOrFail($id);

        try {
            $import = new ParticipantsImport($programa->pro_id, $programa->cur_id);
            Excel::import($import, $request->file('file'));

            return back()->with('success', "Carga masiva finalizada. Inscritos Nuevos: {$import->importedCount}. Usuarios Actualizados: {$import->updatedCount}. Fallidos: {$import->failedCount}.");
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Error al procesar el archivo: ' . $e->getMessage()]);
        }
    }

    /**
     * Gestión de Calificaciones (Req 63, 64, 65)
     */
    public function grades($id)
    {
        $programa = Programa::with(['curso', 'inscripciones.participante', 'inscripciones.informacion'])->findOrFail($id);
        return view('admin.programs.grades', compact('programa'));
    }

    public function updateGrades(Request $request, $id)
    {
        $programa = Programa::findOrFail($id);
        $notas = $request->input('notas', []);
        $asistencias = $request->input('asistencias', []);

        foreach ($notas as $insId => $nota) {
            $inscripcion = Inscripcion::find($insId);
            if ($inscripcion && $inscripcion->pro_id == $id) {
                // Actualizar info en tabla 'informacion'
                $informacion = Informacion::firstOrNew(['ins_id' => $insId]);
                $informacion->inf_nota = $nota;

                if (isset($asistencias[$insId])) {
                    $informacion->inf_asistencia = $asistencias[$insId];
                }

                // Estado: Aprobado si nota >= 4.0
                $informacion->inf_estado = ($nota >= 4.0) ? 1 : 0;

                $informacion->save();
            }
        }

        return back()->with('success', 'Calificaciones actualizadas correctamente.');
    }
    /**
     * Alternar estado de activación (Req 13)
     */
    public function toggleStatus($id)
    {
        $programa = Programa::findOrFail($id);
        $programa->pro_estado = !$programa->pro_estado;
        $programa->save();

        $estado = $programa->pro_estado ? 'activado' : 'desactivado';
        $message = "La sesión ha sido {$estado} correctamente.";

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'new_state' => $programa->pro_estado
            ]);
        }


        return back()->with('success', $message);
    }

    public function importGrades(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt'
        ]);

        $programa = Programa::findOrFail($id);

        try {
            $import = new GradesImport($programa->pro_id);
            Excel::import($import, $request->file('file'));

            return back()->with('success', "Proceso finalizado. Actualizados: {$import->updatedCount}. Fallidos/No encontrados: {$import->failedCount}.");
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar archivo: ' . $e->getMessage());
        }
    }

    public function downloadGradesTemplate($id)
    {
        $programa = Programa::with('inscripciones.participante')->findOrFail($id);

        $filename = "plantilla_notas_curso_{$programa->pro_id}.csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['rut', 'nombre_completo', 'email', 'nota', 'asistencia'];

        $callback = function () use ($programa, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM for Excel
            fputcsv($file, $columns, ';'); // Use semicolon for Latin American Excel default

            foreach ($programa->inscripciones as $ins) {
                fputcsv($file, [
                    $ins->participante->par_login, // RUT
                    $ins->participante->par_nombre . ' ' . $ins->participante->par_apellidos,
                    $ins->participante->par_correo,
                    $ins->ins_nota,
                    $ins->ins_asistencia
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }


    /**
     * Exportar participantes a CSV (Req 58)
     */
    public function exportParticipants($id)
    {
        $programa = Programa::with(['curso', 'inscripciones.participante'])->findOrFail($id);

        $filename = "nomina_participantes_" . $programa->pro_id . "_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['RUT', 'Nombre', 'Apellidos', 'Email', 'Teléfono', 'Fecha Inscripción', 'Estado Asistencia', 'Nota', 'Asistencia %'];

        $callback = function () use ($programa, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($programa->inscripciones as $inscripcion) {
                $participante = $inscripcion->participante;

                // Info extendida
                $nota = $inscripcion->ins_nota;
                $asistencia = $inscripcion->ins_asistencia;

                $row = [
                    $participante->par_rut ?? 'N/A',
                    $participante->par_nombre ?? 'N/A',
                    $participante->par_apellidos ?? '',
                    $participante->par_email ?? '',
                    $participante->par_telefono ?? '',
                    $inscripcion->ins_fecha,
                    $inscripcion->ins_estado ?? 'Pendiente',
                    $nota,
                    $asistencia
                ];

                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * Gestión de Calificaciones Relatores (Req 66-70)
     */
    public function relatorGrades($id)
    {
        $programa = Programa::with(['curso', 'relatores'])->findOrFail($id);
        return view('admin.programs.relator_grades', compact('programa'));
    }

    public function updateRelatorGrades(Request $request, $id)
    {
        $programa = Programa::findOrFail($id);
        $notas = $request->input('notas', []);
        $asistencias = $request->input('asistencias', []);
        $observaciones = $request->input('observaciones', []);

        // Guardar Notas
        foreach ($notas as $relLogin => $nota) {
            if ($programa->relatores()->where('relator.rel_login', $relLogin)->exists()) {
                $attributes = [
                    'rr_nota' => $nota,
                    'rr_asistencia' => $asistencias[$relLogin] ?? null,
                    'rr_observaciones' => $observaciones[$relLogin] ?? null,
                ];

                // Certificar si se solicitó y cumple requisitos
                if ($request->input('action') === 'certify' && $nota >= 4.0) {
                    $attributes['rr_certificado'] = now();
                }

                $programa->relatores()->updateExistingPivot($relLogin, $attributes);
            }
        }

        $msg = ($request->input('action') === 'certify')
            ? 'Relatores calificados y certificados exitosamente.'
            : 'Calificaciones de relatores guardadas correctamente.';

        return back()->with('success', $msg);
    }
    public function certifyTeacher(Request $request, $id, $relLogin)
    {
        $programa = Programa::findOrFail($id);

        // Find pivot or throw 404
        $exists = $programa->relatores()->where('relator.rel_login', $relLogin)->exists();
        if (!$exists) {
            return back()->with('error', 'El relator no está asignado a este programa.');
        }

        // Toggle certification
        // If certified, we might want to un-certify? Or just verify?
        // Let's implement toggle for flexibility.
        $relator = $programa->relatores()->where('relator.rel_login', $relLogin)->first();
        $isCertified = !empty($relator->pivot->rr_certificado);

        $newState = $isCertified ? null : now();
        $message = $isCertified ? 'Certificación anulada.' : 'Relator certificado exitosamente.';

        $programa->relatores()->updateExistingPivot($relLogin, ['rr_certificado' => $newState]);

        return back()->with('success', $message);
    }
}


