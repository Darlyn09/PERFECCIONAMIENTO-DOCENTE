<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Curso;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('participant')->user();

        // Obtener todos los cursos
        $allCourses = $user->cursos()
            ->with(['categoria', 'programas'])
            ->withPivot('ins_id')
            ->orderBy('cur_fecha_inicio', 'desc')
            ->get();

        // Hydrate with Approval Status
        $insIds = $allCourses->pluck('pivot.ins_id');
        $infos = \App\Models\Informacion::whereIn('ins_id', $insIds)->get()->keyBy('ins_id');

        $allCourses->each(function ($course) use ($infos) {
            $insId = $course->pivot->ins_id;
            $info = $infos->get($insId);
            $course->is_approved = ($info && $info->inf_estado == 1);

            // Logic correction: Finished if not state 1 OR (Active state 1 AND Approved)
            // This allows Approved active courses to appear in 'History' if desired, 
            // or effectively be treated as 'completed' for certification purposes.
        });

        // Separar activos y finalizados
        // Update: considers 'finished' if state != 1 OR is_approved
        $activeCourses = $allCourses->filter(function ($course) {
            return $course->cur_estado == 1 && !$course->is_approved;
        });

        $finishedCourses = $allCourses->filter(function ($course) {
            return $course->cur_estado != 1 || $course->is_approved;
        });

        // Obtener TODAS las categorías del sistema
        $allCategories = \App\Models\Categoria::all();
        $totalCourses = $allCourses->count();

        // Calcular estadísticas y niveles por categoría
        // Mapeamos ID de categoría -> Contador
        $userCategoryCounts = [];
        foreach ($allCourses as $course) {
            // Usamos cur_categoria (ID) si disponible
            if ($course->categoria) {
                $catId = $course->categoria->cur_categoria;
                if (!isset($userCategoryCounts[$catId])) {
                    $userCategoryCounts[$catId] = 0;
                }
                $userCategoryCounts[$catId]++;
            }
        }

        // Definir Niveles
        $getLevel = function ($count) {
            if ($count >= 10)
                return ['label' => 'Maestro', 'color' => 'purple', 'icon' => 'fa-chess-king'];
            if ($count >= 6)
                return ['label' => 'Especialista', 'color' => 'blue', 'icon' => 'fa-medal'];
            if ($count >= 3)
                return ['label' => 'Practicante', 'color' => 'emerald', 'icon' => 'fa-running'];
            if ($count > 0)
                return ['label' => 'Explorador', 'color' => 'yellow', 'icon' => 'fa-compass'];
            return ['label' => 'Novato', 'color' => 'slate', 'icon' => 'fa-seedling']; // Nivel 0
        };

        $categoryProgress = [];
        foreach ($allCategories as $cat) {
            $count = $userCategoryCounts[$cat->cur_categoria] ?? 0;
            $levelData = $getLevel($count);

            $categoryProgress[] = [
                'name' => $cat->nom_categoria, // Asumiendo nom_categoria basado en Categoria.php
                'count' => $count,
                'percentage' => $totalCourses > 0 ? round(($count / max(1, $totalCourses)) * 100) : 0,
                'level' => $levelData['label'],
                'color' => $levelData['color'],
                'icon' => $levelData['icon']
            ];
        }

        // Ordenar: Primero los que tienen avance desc, luego alfabético
        usort($categoryProgress, function ($a, $b) {
            if ($a['count'] === $b['count']) {
                return strcmp($a['name'], $b['name']);
            }
            return $b['count'] <=> $a['count'];
        });

        // Pasar variable enrolledCourses como alias de allCourses para compatibilidad si se usa
        $enrolledCourses = $allCourses;

        return view('participant.profile', compact(
            'user',
            'enrolledCourses',
            'activeCourses',
            'finishedCourses',
            'categoryProgress',
            'totalCourses'
        ));
    }

    public function security()
    {
        return view('participant.security');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::guard('participant')->user();

        // Verificar contraseña actual
        // Nota: Participante usa 'par_password' y bcrypt? 
        // Si el sistema usa Hash, esto funciona. Si es texto plano (legacy), habría que chequear eso.
        // Asumiremos Hash standard de Laravel.

        if (!Hash::check($request->current_password, $user->par_password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no es correcta.']);
        }

        $user->par_password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
