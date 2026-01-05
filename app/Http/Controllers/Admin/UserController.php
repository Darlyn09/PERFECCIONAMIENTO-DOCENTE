<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Participante;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Participante::query();

        // 🔍 Búsqueda
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('par_nombre', 'like', "%$search%")
                    ->orWhere('par_apellido', 'like', "%$search%")
                    ->orWhere('par_login', 'like', "%$search%")
                    ->orWhere('par_correo', 'like', "%$search%");
            });
        }

        $curso = null;
        // 🎓 Filtrar por Curso
        if ($request->filled('curso_id')) {
            $curso = \App\Models\Curso::find($request->curso_id);
            $query->join('inscripcion', 'participante.par_login', '=', 'inscripcion.par_login')
                ->where('inscripcion.cur_id', $request->curso_id)
                ->select('participante.*'); // Evitar columnas duplicadas
        }

        // 📅 Filtrar por Evento
        if ($request->filled('evento_id')) {
            $query->join('participacion', 'participante.par_login', '=', 'participacion.par_participa')
                ->where('participacion.eve_id', $request->evento_id)
                ->select('participante.*'); // Evitar columnas duplicadas
        }

        // Filtro por Rol
        if ($request->filled('rol')) {
            $query->where('par_perfil', $request->rol);
        }

        // 📊 Estadísticas (Respetando filtros)
        $totalUsuarios = $query->clone()->count();
        $totalAdmins = $query->clone()->where('par_perfil', 'admin')->count();
        $totalRegular = $query->clone()->where('par_perfil', '!=', 'admin')->count();

        // 👥 Usuarios = todos los participantes
        $usuarios = $query->orderBy('par_nombre')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', compact('usuarios', 'totalUsuarios', 'totalAdmins', 'totalRegular', 'curso'));
    }

    public function export(Request $request)
    {
        $query = Participante::query();

        // Aplicar mismos filtros que en index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('par_nombre', 'like', "%$search%")
                    ->orWhere('par_apellido', 'like', "%$search%")
                    ->orWhere('par_login', 'like', "%$search%")
                    ->orWhere('par_correo', 'like', "%$search%");
            });
        }

        if ($request->filled('curso_id')) {
            $query->join('inscripcion', 'participante.par_login', '=', 'inscripcion.par_login')
                ->where('inscripcion.cur_id', $request->curso_id)
                ->select('participante.*');
        }

        if ($request->filled('evento_id')) {
            $query->join('participacion', 'participante.par_login', '=', 'participacion.par_participa')
                ->where('participacion.eve_id', $request->evento_id)
                ->select('participante.*');
        }

        if ($request->filled('rol')) {
            $query->where('par_perfil', $request->rol);
        }

        $usuarios = $query->get();
        $filename = "usuarios_" . date('Ymd_His') . ".csv";

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['RUT/Login', 'Nombre', 'Apellidos', 'Correo', 'Perfil', 'Cargo', 'Facultad', 'Departamento', 'Sede', 'Teléfono/Anexo', 'Fecha Registro'];

        $callback = function () use ($usuarios, $columns) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM
            fputcsv($file, $columns, ';'); // Separador ; para Excel en español

            foreach ($usuarios as $u) {
                $row = [
                    $u->par_login,
                    $u->par_nombre,
                    $u->par_apellido,
                    $u->par_correo,
                    $u->par_perfil,
                    $u->par_cargo,
                    $u->par_facultad,
                    $u->par_departamento,
                    $u->par_sede,
                    $u->par_anexo,
                    $u->fecha_registro,
                ];
                fputcsv($file, $row, ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $usuario = Participante::findOrFail($id);

        // Query base
        $query = Inscripcion::join('curso', 'inscripcion.cur_id', '=', 'curso.cur_id')
            ->leftJoin('categoria', 'curso.cur_categoria', '=', 'categoria.cur_categoria')
            ->join('evento', 'curso.eve_id', '=', 'evento.eve_id')
            ->leftJoin('informacion', 'inscripcion.ins_id', '=', 'informacion.ins_id') // Join para estado de aprobación
            ->where('inscripcion.par_login', $id)
            ->select(
                'curso.*',
                'categoria.nom_categoria',
                'inscripcion.ins_id', // Necesario para referencia
                'inscripcion.ins_date as fecha_inscripcion',
                'informacion.inf_nota', // CORRECTO: inf_nota de tabla informacion
                'evento.eve_finaliza',
                'informacion.inf_estado'
            )
            ->orderBy('inscripcion.ins_date', 'desc');

        // Obtener todos para estadísticas
        $allInscripciones = $query->clone()->get();

        // Obtener paginados para la vista
        $inscripciones = $query->paginate(10);

        // Estadísticas (usando $allInscripciones)
        $totalCursos = $allInscripciones->count();

        // Cursos Aprobados: Nota >= 4.0 o Estado Aprobado (Legacy)
        $cursosAprobados = $allInscripciones->filter(function ($ins) {
            return $ins->isApproved();
        })->count();

        // Áreas desarrolladas (usando $allInscripciones)
        $areas = $allInscripciones->groupBy('nom_categoria')
            ->map(function ($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);

        return view('admin.users.show', compact('usuario', 'inscripciones', 'totalCursos', 'cursosAprobados', 'areas'));
    }
    public function create()
    {
        return view('admin.users.create');
    }

    public function searchByRut(Request $request)
    {
        $query = Participante::query();

        if ($request->has('rut') && $request->rut != '') {
            $query->where('par_login', $request->rut);
        } else if ($request->has('email') && $request->email != '') {
            $query->where('par_correo', $request->email);
        } else {
            return response()->json(null);
        }

        $usuario = $query->first();
        return response()->json($usuario);
    }

    public function store(Request $request)
    {
        // 1. Verificar si el usuario ya existe por RUT (par_login)
        $existingUser = Participante::where('par_login', $request->par_login)->first();

        // 2. Si existe Y estamos en contexto de inscripción (curso_id)
        if ($existingUser && $request->filled('curso_id')) {
            $this->enrollUser($existingUser, $request->curso_id);
            return redirect()->route('admin.courses.show', $request->curso_id)
                ->with('success', 'Usuario existente encontrado e inscrito correctamente en el curso.');
        }

        // 3. Si existe Y NO hay curso (Creación pura) -> Error duplicate
        if ($existingUser) {
            return back()->withErrors(['par_login' => 'El RUT/Login ya se encuentra registrado en el sistema.'])->withInput();
        }

        // 4. Si NO existe -> Validación estándar de creación
        $validated = $request->validate([
            'par_login' => 'required|string|unique:participante,par_login', // Usar input login
            'par_nombre' => 'required|string',
            'par_apellido' => 'required|string',
            'par_correo' => 'required|email|unique:participante,par_correo',
            'par_password' => 'required|string|min:6',
            'par_perfil' => 'required|string',
            'par_facultad' => 'nullable|string',
            'par_departamento' => 'nullable|string',
            'par_sede' => 'nullable|string',
            'par_cargo' => 'required|string',
            'par_anexo' => 'required|string',
        ], [
            'par_login.unique' => 'El RUT/Login ya está registrado.',
            'par_correo.unique' => 'El correo electrónico ya está registrado.',
            'par_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'par_cargo.required' => 'El cargo es obligatorio.',
            'par_anexo.required' => 'El anexo es obligatorio.',
        ]);

        $usuario = new Participante();
        $usuario->par_login = $validated['par_login']; // Corregido: Usar RUT real
        $usuario->par_nombre = $validated['par_nombre'];
        $usuario->par_apellido = $validated['par_apellido'];
        $usuario->par_correo = $validated['par_correo'];
        $usuario->par_password = Hash::make($validated['par_password']);
        $usuario->par_perfil = $validated['par_perfil'];
        $usuario->par_cargo = $validated['par_cargo'];
        $usuario->par_anexo = $validated['par_anexo'];
        $usuario->par_facultad = $validated['par_facultad'];
        $usuario->par_departamento = $validated['par_departamento'];
        $usuario->par_sede = $validated['par_sede'];
        $usuario->fecha_registro = now();
        $usuario->last_login_at = null;
        $usuario->save();

        // 5. Inscribir si corresponde
        if ($request->filled('curso_id')) {
            $this->enrollUser($usuario, $request->curso_id);
            return redirect()->route('admin.courses.show', $request->curso_id)
                ->with('success', 'Usuario creado e inscrito en el curso exitosamente.');
        }

        return redirect()->route('admin.users.index')->with('success', 'Usuario creado exitosamente. Login: ' . $usuario->par_login);
    }

    // Helper para inscripción (Privado)
    private function enrollUser($usuario, $cursoId)
    {
        $existe = Inscripcion::where('cur_id', $cursoId)->where('par_login', $usuario->par_login)->exists();
        if (!$existe) {
            $inscripcion = new Inscripcion();
            $inscripcion->cur_id = $cursoId;
            $inscripcion->par_login = $usuario->par_login;
            $inscripcion->ins_date = now();
            // $inscripcion->ins_perfil = 'alumno'; // Asumido o default DB
            $inscripcion->save();
        }
    }

    public function edit($id)
    {
        $usuario = Participante::findOrFail($id);
        return view('admin.users.edit', compact('usuario'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Participante::findOrFail($id);

        $validated = $request->validate([
            'par_nombre' => 'required|string',
            'par_apellido' => 'required|string',
            'par_correo' => 'required|email|unique:participante,par_correo,' . $id . ',par_login',
            'par_perfil' => 'required|string',
            'par_facultad' => 'nullable|string',
            'par_departamento' => 'nullable|string',
            'par_sede' => 'nullable|string',
            'par_cargo' => 'required|string',
            'par_anexo' => 'required|string',
            'par_password' => 'nullable|string|min:6', // Optional on update
        ], [
            'par_correo.unique' => 'El correo electrónico ya está en uso por otro usuario.',
            'par_password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'par_cargo.required' => 'El cargo es obligatorio.',
            'par_anexo.required' => 'El anexo es obligatorio.',
        ]);

        $usuario->par_nombre = $validated['par_nombre'];
        $usuario->par_apellido = $validated['par_apellido'];
        $usuario->par_correo = $validated['par_correo'];
        $usuario->par_perfil = $validated['par_perfil'];
        $usuario->par_facultad = $validated['par_facultad'];
        $usuario->par_departamento = $validated['par_departamento'];
        $usuario->par_sede = $validated['par_sede'];
        $usuario->par_cargo = $validated['par_cargo'];
        $usuario->par_anexo = $validated['par_anexo'];

        if ($request->filled('par_password')) {
            $usuario->par_password = Hash::make($validated['par_password']);
        }

        $usuario->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        try {
            $usuario = Participante::findOrFail($id);
            $usuario->delete();
            return redirect()->route('admin.users.index')->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'No se puede eliminar el usuario. Puede tener registros asociados.');
        }
    }

    // 📧 Reenviar Credenciales
    public function resendCredentials($id)
    {
        $usuario = Participante::findOrFail($id);

        if (!$usuario->par_correo) {
            return back()->with('error', 'El usuario no tiene un correo electrónico registrado.');
        }

        // Generar contraseña aleatoria
        $newPassword = \Illuminate\Support\Str::random(10);

        // Guardar nueva contraseña (hasheada)
        $usuario->par_password = Hash::make($newPassword);
        $usuario->save();

        // Enviar Correo (con la contraseña plana)
        try {
            \Illuminate\Support\Facades\Mail::to($usuario->par_correo)
                ->send(new \App\Mail\UserCredentialsMail($usuario->par_nombre, $usuario->par_correo, $newPassword));

            return back()->with('success', 'Credenciales restablecidas y enviadas por correo exitosamente.');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al enviar credenciales: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    // 🗑️ Acción Masiva: Eliminar
    public function massDestroy(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids)) {
            return back()->with('error', 'No has seleccionado ningún usuario.');
        }

        $exitos = 0;
        $errores = 0;

        foreach ($ids as $id) {
            try {
                $usuario = Participante::findOrFail($id);
                // Validar que no sea uno mismo?
                // if ($usuario->par_login === auth()->user()->par_login) continue; 

                $usuario->delete();
                $exitos++;
            } catch (\Exception $e) {
                $errores++;
            }
        }

        if ($errores > 0) {
            return back()->with('warning', "Se eliminaron $exitos usuarios. $errores no se pudieron eliminar por tener registros asociados.");
        }

        return back()->with('success', "$exitos usuarios eliminados exitosamente.");
    }
}
