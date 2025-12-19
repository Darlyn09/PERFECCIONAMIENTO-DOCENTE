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

            Programa::create($data);

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
        $programa = Programa::with('curso')->findOrFail($id);
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

            return redirect()->route('admin.courses.show', $programa->cur_id)
                ->with('success', 'Programa actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el programa: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar programa
     */
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
    public function exportParticipants($id)
    {
        $programa = Programa::with(['curso', 'inscripciones.participante'])->findOrFail($id);

        $filename = "nomina_participantes_" . $programa->pro_id . "_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['RUT', 'Nombre', 'Apellidos', 'Email', 'Teléfono', 'Fecha Inscripción', 'Estado Asistencia', 'Nota'];

        $callback = function () use ($programa, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns); // UTF-8 BOM if needed: fputs($file, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

            foreach ($programa->inscripciones as $inscripcion) {
                $participante = $inscripcion->participante;

                $row = [
                    $participante->par_rut ?? 'N/A',
                    $participante->par_nombre ?? 'N/A',
                    $participante->par_apellidos ?? '',
                    $participante->par_email ?? '',
                    $participante->par_telefono ?? '',
                    $inscripcion->ins_fecha,
                    $inscripcion->ins_asistencia ?? '0',
                    $inscripcion->ins_nota ?? '0',
                ];

                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


