<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Models\Categoria;
use Illuminate\Support\Facades\Auth;

class CatalogController extends Controller
{
    /**
     * Display a listing of courses with filters.
     * Req 74: Filtrar y buscar por nombre, categoría, disponibilidad y fecha.
     */
    public function index(Request $request)
    {
        $query = Curso::query()->where('cur_estado', '!=', 0); // Assuming 0 is deleted/hidden

        // Filter by Name
        if ($request->has('search') && $request->search != '') {
            $query->where('cur_nombre', 'like', '%' . $request->search . '%');
        }

        // Filter by Category
        if ($request->has('category') && $request->category != '') {
            $query->where('cur_categoria', $request->category);
        }

        // Filter by Status/Availability (Req 78)
        // Note: Availability logic can be complex (dates vs slots).
        // Here we filter by broad status if provided.
        // Or we can add specific scopes.

        // Filter by Start Date
        if ($request->has('date_start') && $request->date_start != '') {
            $query->whereDate('cur_fecha_inicio', '>=', $request->date_start);
        }

        $courses = $query->orderBy('cur_fecha_inicio', 'asc')->paginate(9);
        $categories = Categoria::all();

        return view('participant.catalog.index', compact('courses', 'categories'));
    }

    /**
     * Display the specified course.
     * Req 76 & 77: Full details (dates, description, relators, contents, etc).
     */
    public function show($id)
    {
        $course = Curso::with(['evento', 'categoria'])->findOrFail($id);

        // Check enrollment status for current user
        $isEnrolled = false;
        if (Auth::check()) {
            $isEnrolled = Inscripcion::where('par_id', Auth::user()->par_id)
                ->where('cur_id', $id)
                ->where('ins_estado', 1) // Only active
                ->exists();
        }

        // Req 75: Auto update availability
        $slots = $course->cur_cupos;
        $enrolledCount = $course->inscripciones()->where('ins_estado', 1)->count();
        $isFull = !is_null($slots) && $enrolledCount >= $slots;
        $slotsRemaining = !is_null($slots) ? max(0, $slots - $enrolledCount) : null;

        return view('participant.catalog.show', compact('course', 'isEnrolled', 'isFull', 'slotsRemaining'));
    }

    /**
     * Enroll in a course.
     */
    public function enroll($id)
    {
        $course = Curso::findOrFail($id);
        $user = Auth::user();

        // Validation: Already enrolled?
        $exists = Inscripcion::where('par_id', $user->par_id)->where('cur_id', $id)->where('ins_estado', 1)->exists();
        if ($exists) {
            return back()->with('error', 'Ya estás inscrito en este curso.');
        }

        // Validation: Slots (Req 75/79)
        if (!is_null($course->cur_cupos)) {
            $enrolledCount = $course->inscripciones()->where('ins_estado', 1)->count();
            if ($enrolledCount >= $course->cur_cupos) {
                return back()->with('error', 'Lo sentimos, no hay cupos disponibles.');
            }
        }

        // Create Inscripcion
        Inscripcion::create([
            'par_id' => $user->par_id,
            'cur_id' => $id,
            'ins_fecha_inscripcion' => now(),
            'ins_estado' => 1 // Active
        ]);

        return redirect()->route('participant.catalog.show', $id)->with('success', 'Inscripción realizada con éxito.');
    }

    /**
     * Cancel enrollment (Req 80).
     */
    public function cancel($id)
    {
        $user = Auth::user();
        $inscripcion = Inscripcion::where('par_id', $user->par_id)->where('cur_id', $id)->firstOrFail();

        // Validation: Can cancel? (e.g., before start date)
        $course = Curso::findOrFail($id);
        if ($course->cur_fecha_inicio && now()->gte($course->cur_fecha_inicio)) {
            return back()->with('error', 'No puedes cancelar la inscripción una vez iniciado el curso.');
        }

        $inscripcion->delete(); // Or set status to 'Cancelled'

        return back()->with('success', 'Inscripción cancelada.');
    }
}
