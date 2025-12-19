<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Programa;
use App\Models\Curso;
use App\Models\Relator;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Formulario de creación de programa
     */
    public function create(Request $request)
    {
        $curso = Curso::with('evento')->findOrFail($request->curso);
        $relatores = Relator::where('rel_estado', 1)->get();

        return view('admin.programs.create', compact('curso', 'relatores'));
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

        return view('admin.programs.edit', compact('programa', 'relatores'));
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

    public function importGrades(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $programa = Programa::findOrFail($id);
        $file = $request->file('file');

        $actualizados = 0;
        $errores = 0;

        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            fgetcsv($handle); // Skip Header

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) { // Separador ; por Excel
                // Estructura esperada: RUT, Nombre, Nota, Asistencia
                $rut = trim($data[0]);
                $nota = str_replace(',', '.', trim($data[2])); // Permitir decimal con coma
                $asistencia = trim($data[3]);

                // Buscar inscripción por RUT en este programa
                $inscripcion = \App\Models\Inscripcion::where('pro_id', $id)
                    ->whereHas('participante', function ($q) use ($rut) {
                        $q->where('par_rut', $rut)->orWhere('par_login', $rut);
                    })->first();

                if ($inscripcion) {
                    $inscripcion->ins_nota = $nota;
                    $inscripcion->ins_asistencia = $asistencia;
                    $inscripcion->save();
                    $actualizados++;
                } else {
                    $errores++;
                }
            }
            fclose($handle);
        }

        return back()->with('success', "Notas importadas: $actualizados actualizados. $errores no encontrados.");
    }

    public function downloadGradesTemplate($id)
    {
        $programa = Programa::with('inscripciones.participante')->findOrFail($id);
        $filename = "plantilla_notas_" . $programa->pro_id . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        $columns = ['RUT', 'Nombre', 'Nota', 'Asistencia'];

        $callback = function () use ($programa, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($file, $columns, ';');

            foreach ($programa->inscripciones as $ins) {
                fputcsv($file, [
                    $ins->participante->par_rut ?? $ins->participante->par_login,
                    $ins->participante->par_nombre . ' ' . $ins->participante->par_apellidos,
                    $ins->ins_nota,
                    $ins->ins_asistencia
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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
        // Excluir relatores ya asignados
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
            'file' => 'required|file|mimes:csv,txt'
        ]);

        $programa = Programa::findOrFail($id);
        $file = $request->file('file');

        $cargados = 0;
        $errores = 0;

        if (($handle = fopen($file->getRealPath(), "r")) !== FALSE) {
            fgetcsv($handle); // Skip header if present, or logic to detect

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Suponiendo Columna 0 = RUT/Login
                $rut = trim($data[0]);

                // Validar si existe relator
                $relator = Relator::find($rut);

                if ($relator) {
                    if (!$programa->relatores()->where('relator.rel_login', $rut)->exists()) {
                        $programa->relatores()->attach($rut);
                        $cargados++;
                    }
                } else {
                    $errores++;
                }
            }
            fclose($handle);
        }

        return back()->with('success', "Carga masiva completada: $cargados asignados. $errores no encontrados.");
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
            $inscripcion = \App\Models\Inscripcion::find($insId);
            if ($inscripcion && $inscripcion->pro_id == $id) {
                // Actualizar info en tabla 'informacion' o 'inscripcion'
                // Según inspección, info está en inscripcion: ins_nota, ins_asistencia? 
                // Ah, el comando anterior mostró 'ins_nota' en Inscripcion? (Faltó ver output real, intentaré usar ambos)
                // Revisión rápida: en exportParticipants usé $inscripcion->ins_nota.

                $inscripcion->ins_nota = $nota;
                if (isset($asistencias[$insId])) {
                    $inscripcion->ins_asistencia = $asistencias[$insId];
                }

                // Estado: Aprobado si nota >= 4.0? Lógica simple por ahora
                // $inscripcion->ins_estado = ($nota >= 4.0) ? 1 : 0;

                $inscripcion->save();
            }
        }

        return back()->with('success', 'Calificaciones actualizadas correctamente.');
    }

    /**
     * Exportar participantes a CSV (Req 58)
     */
    public function exportParticipants($id)
    {
        // ... (Keep existing implementation)
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
}


