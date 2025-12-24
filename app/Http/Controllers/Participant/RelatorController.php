<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Informacion;
use App\Models\Relator;
use App\Models\Participante;

class RelatorController extends Controller
{
    /**
     * Muestra la lista de cursos del relator autenticado (Participante).
     */
    public function index()
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado. No se encontró un perfil de relator asociado a su cuenta.');
        }

        // Obtener EVENTOS que contienen cursos dictados por este relator
        // Agrupamos por eventos para la vista jerárquica
        $rel_login = $participant->relator->rel_login;

        $eventos = \App\Models\Evento::whereHas('cursos.programas', function ($q) use ($rel_login) {
            $q->where('rel_login', $rel_login);
        })
            ->with([
                'cursos' => function ($q) use ($rel_login) {
                    // Filtrar solo los cursos que este relator dicta dentro del evento
                    $q->whereHas('programas', function ($q2) use ($rel_login) {
                        $q2->where('rel_login', $rel_login);
                    })->with([
                                'programas' => function ($q3) use ($rel_login) {
                            $q3->where('rel_login', $rel_login); // Cargar solo los programas del relator
                        }
                            ]);
                }
            ])
            ->orderBy('eve_id', 'desc')
            ->paginate(5);

        return view('participant.relator.my-courses', compact('eventos'));
    }

    /**
     * Muestra el formulario para crear un nuevo curso.
     */
    public function create()
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado.');
        }

        $eventos = \App\Models\Evento::where('eve_estado', 1)->orderBy('eve_id', 'desc')->get();
        $categorias = \App\Models\Categoria::all();
        // Cargar participantes para selección de colaboradores
        $participantes = Participante::select('par_login', 'par_nombre', 'par_apellido')
            ->orderBy('par_nombre')
            ->get();

        return view('participant.relator.create_course', compact('eventos', 'categorias', 'participantes'));
    }

    /**
     * Almacena un nuevo curso y crea la primera sesión (programa) para el relator.
     */
    public function store(Request $request)
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado.');
        }

        $validated = $request->validate([
            'cur_nombre' => 'required|max:255',
            'eve_id' => 'required|exists:evento,eve_id',
            'cur_categoria' => 'required|exists:categoria,cur_categoria',
            'cur_horas' => 'required|numeric|min:1',
            'cur_asistencia' => 'required|numeric|min:0|max:100',
            'cur_modalidad' => 'required',
            'cur_fecha_inicio' => 'required|date',
            'cur_fecha_termino' => 'required|date|after_or_equal:cur_fecha_inicio',
            'pro_horario' => 'nullable|string|max:255',
            'pro_lugar' => 'nullable|string|max:255',
            'cur_link' => 'nullable|url|max:255',
            'cur_descripcion' => 'required',
            'cur_objetivos' => 'required',
            'cur_contenidos' => 'required',
            'cur_metodologias' => 'required',
            'cur_bibliografia' => 'required',
            'cur_aprobacion' => 'required',
        ]);

        // 1. Crear el Curso
        $curso = new Curso();
        $curso->eve_id = $request->eve_id;
        $curso->cur_nombre = $request->cur_nombre;
        $curso->cur_descripcion = $request->cur_descripcion;
        $curso->cur_objetivos = $request->cur_objetivos;
        $curso->cur_contenidos = $request->cur_contenidos;
        $curso->cur_metodologias = $request->cur_metodologias;
        $curso->cur_bibliografia = $request->cur_bibliografia;
        $curso->cur_aprobacion = $request->cur_aprobacion;
        $curso->cur_categoria = $request->cur_categoria;
        $curso->cur_horas = $request->cur_horas;
        $curso->cur_asistencia = $request->cur_asistencia;
        $curso->cur_modalidad = $request->cur_modalidad;
        $curso->cur_fecha_inicio = $request->cur_fecha_inicio;
        $curso->cur_fecha_termino = $request->cur_fecha_termino;
        $curso->cur_link = $request->cur_link; // Guardar link
        $curso->cur_estado = 1; // Activo por defecto
        $curso->save();

        // 2. Crear el Programa (Sesión) asociado al Curso y al Relator
        $programa = new \App\Models\Programa();
        $programa->cur_id = $curso->cur_id;
        $programa->rel_login = $participant->relator->rel_login;
        $programa->pro_inicia = $request->cur_fecha_inicio; // Usamos las mismas fechas para la primera sesión
        $programa->pro_finaliza = $request->cur_fecha_termino;
        $programa->pro_horario = $request->pro_horario;
        $programa->pro_lugar = $request->pro_lugar; // Guardar ubicación texto
        $programa->pro_horario = $request->pro_horario;
        $programa->pro_lugar = $request->pro_lugar; // Guardar ubicación texto
        $programa->pro_colaboradores = $request->input('pro_colaboradores') ?? ''; // Guardar colaboradores seleccionados

        // Completar campos obligatorios de fechas y horas
        $programa->pro_abre = $request->cur_fecha_inicio;
        $programa->pro_cierra = $request->cur_fecha_termino;
        $programa->pro_hora_inicio = '09:00:00';
        $programa->pro_hora_termino = '18:00:00';

        // Valores por defecto
        $programa->pro_cupos = 100; // Valor razonable por defecto
        $programa->save();

        return redirect()->route('participant.relator.my_courses')->with('success', 'Curso y sesión creados correctamente.');
    }

    /**
     * Muestra la lista de alumnos inscritos en un curso específico.
     */
    public function students($curso_id)
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado.');
        }

        // Verificar que el curso pertenece al relator (a través de sus programas)
        $programas = $participant->relator->programas()->where('cur_id', $curso_id)->get();

        if ($programas->isEmpty()) {
            abort(403, 'No tiene permiso para gestionar este curso.');
        }

        $curso = Curso::findOrFail($curso_id);

        // Obtener inscripciones SOLO de los programas del relator
        $programasIds = $programas->pluck('pro_id');

        $inscripciones = Inscripcion::with(['participante', 'informacion'])
            ->whereIn('pro_id', $programasIds)
            ->get()
            ->groupBy('pro_id');

        return view('participant.relator.course_students', compact('curso', 'inscripciones', 'programas'));


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

        $participant = Auth::guard('participant')->user();

        // Verificar permisos (el curso debe ser del relator)
        $esCursoDelRelator = $participant->relator->cursos()->where('curso.cur_id', $inscripcion->cur_id)->exists();

        if (!$esCursoDelRelator) {
            abort(403, 'No tiene permiso para gestionar este curso.');
        }

        // Buscar o crear registro en informacion
        $informacion = Informacion::firstOrNew(['ins_id' => $ins_id]);
        $informacion->inf_estado = $request->estado ? 1 : 0;
        $informacion->save();

        return back()->with('success', 'Estado de aprobación actualizado correctamente.');
    }

    /**
     * Gestión de Calificaciones (Grades)
     */
    public function grades($pro_id)
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado.');
        }

        // Verificar que el programa pertenece al relator (Directo o Colaborador)
        $programa = \App\Models\Programa::with(['curso', 'inscripciones.participante'])
            ->where('pro_id', $pro_id)
            ->where(function ($q) use ($participant) {
                $rel_login = $participant->relator->rel_login;
                $q->where('rel_login', $rel_login)
                    ->orWhereHas('relatores', function ($q2) use ($rel_login) {
                        $q2->where('relator.rel_login', $rel_login);
                    });
            })
            ->firstOrFail();

        return view('participant.relator.grades', compact('programa'));
    }

    public function updateGrades(Request $request, $pro_id)
    {
        $participant = Auth::guard('participant')->user();
        if (!$participant || !$participant->relator)
            abort(403);

        $programa = \App\Models\Programa::findOrFail($pro_id);

        // Security check
        $rel_login = $participant->relator->rel_login;
        $isRelator = ($programa->rel_login == $rel_login) ||
            $programa->relatores()->where('relator.rel_login', $rel_login)->exists();

        if (!$isRelator)
            abort(403, 'No autorizado para este programa.');

        $notas = $request->input('notas', []);
        $asistencias = $request->input('asistencias', []);

        foreach ($notas as $insId => $nota) {
            $inscripcion = Inscripcion::find($insId);
            if ($inscripcion && $inscripcion->pro_id == $pro_id) {
                // Use Informacion table
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
    use \App\Traits\CertificateGeneratorTrait;

    /**
     * Descarga el certificado de participación como docente para un programa (sesión) específico.
     */
    public function downloadCertificate($programId)
    {
        $participant = Auth::guard('participant')->user();

        if (!$participant || !$participant->relator) {
            abort(403, 'Acceso denegado.');
        }

        $relator = $participant->relator;

        // 1. Fetch Program and Verify Pivot
        $programa = \App\Models\Programa::with(['curso', 'relatores'])->findOrFail($programId);

        // Check if relator is assigned via pivot
        $pivot = $programa->relatores()->where('relator.rel_id', $relator->rel_id)->first();

        if (!$pivot) {
            abort(403, 'Usted no está asignado a esta sesión/programa.');
        }

        // 2. Check configuration
        $cert = \App\Models\Certificado::where('tipo', 'curso')
            ->where('referencia_id', $programa->pro_cur_id)
            ->first();

        if (!$cert) {
            $cert = \App\Models\Certificado::where('tipo', 'defecto')->latest()->first();
        }

        // 3. Data Setup
        $data = $cert;
        if (!$cert) {
            $data = [
                'width' => 800,
                'height' => 600,
                'settings' => ['title_text' => 'CERTIFICADO DOCENTE', 'body_text' => 'Se certifica que:']
            ];
        }

        // 4. Verification URL
        // $verificationUrl = config('app.url') . "/certificates/verify/docente/" . $programa->pro_id; // Mock
        $verificationUrl = route('certificates.verify', ['user' => 'docente', 'course' => $programa->pro_id]); // Use verify route but params might need logic

        if (is_object($data)) {
            $certArray = $data->toArray();
            $certArray['verification_url'] = $verificationUrl;
            $data = $certArray;
        } else {
            $data['verification_url'] = $verificationUrl;
        }

        // 5. Generate
        $generated = $this->generateHtmlCss($data);

        // 6. Replacements
        $replacements = [
            '{nombre_participante}' => mb_strtoupper($relator->rel_nombres . ' ' . $relator->rel_apellidos),
            '{rut_participante}' => $relator->rel_rut,
            '{nombre_curso}' => mb_strtoupper($programa->curso->cur_nombre),
            '{fecha_inicio}' => $programa->pro_fecha_inicio,
            '{fecha_termino}' => $programa->pro_fecha_termino,
            '{horas_curso}' => $programa->curso->cur_horas,
            '{nombre_relator}' => '', // Self
            // Compat
            '{alumno}' => mb_strtoupper($relator->rel_nombres . ' ' . $relator->rel_apellidos),
            '{curso}' => mb_strtoupper($programa->curso->cur_nombre),
            '{fecha}' => now()->format('d/m/Y'),
            '{nombre}' => mb_strtoupper($relator->rel_nombres . ' ' . $relator->rel_apellidos),
        ];

        $html = $this->applyReplacements($generated['html'], $replacements);

        $pdf = \PDF::loadHTML($html);
        $pdf->setPaper([0, 0, $generated['css']['width'] ?? 800, $generated['css']['height'] ?? 600]);

        return $pdf->download('Certificado_Relator_' . $programId . '.pdf');
    }
}
