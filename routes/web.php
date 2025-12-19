<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\AssessmentController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\ParticipantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\CertificateController;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Verificación de Certificados (Pública)
Route::get('/certificates/verify/{user}/{course}', [App\Http\Controllers\CertificateVerificationController::class, 'verify'])->name('certificates.verify')->middleware('signed');
Route::get('/certificates/validate/{id}/{hash}', [App\Http\Controllers\CertificateVerificationController::class, 'validateCertificate'])->name('certificates.validate');

Route::get('/debug-now', function () {
    $now = now();

    echo "<h1>Deep Debug Event Visibility</h1>";
    echo "<h3>System Status</h3>";
    echo "<ul>";
    echo "<li><strong>App Timezone:</strong> " . config('app.timezone') . "</li>";
    echo "<li><strong>PHP Timezone:</strong> " . date_default_timezone_get() . "</li>";
    echo "<li><strong>NOW (Carbon):</strong> " . $now->format('Y-m-d H:i:s') . "</li>";
    echo "<li><strong>NOW (PHP date):</strong> " . date('Y-m-d H:i:s') . "</li>";
    echo "</ul>";

    echo "<h3>Events (First 20 latest)</h3>";
    $events = \App\Models\Evento::orderBy('eve_inicia', 'desc')->take(20)->get();

    echo "<table border='1' cellpadding='5' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>
            <th>ID</th>
            <th>Nombre</th>
            <th>Estado</th>
            <th>Inicio</th>
            <th>Fin</th>
            <th>Status Logic</th>
          </tr>";

    foreach ($events as $e) {
        $start = \Carbon\Carbon::parse($e->eve_inicia);
        $end = \Carbon\Carbon::parse($e->eve_finaliza);

        $isCurrent = $now->between($start, $end);
        $isFuture = $end->greaterThanOrEqualTo($now);
        $startIsFuture = $start->greaterThan($now);

        $rowColor = $isCurrent ? '#dff0d8' : ($isFuture ? '#d9edf7' : '#f2dede');

        echo "<tr style='background: {$rowColor};'>";
        echo "<td>{$e->eve_id}</td>";
        echo "<td>{$e->eve_nombre}</td>";
        echo "<td>{$e->eve_estado}</td>";
        echo "<td>{$e->eve_inicia}</td>";
        echo "<td>{$e->eve_finaliza}</td>";
        echo "<td>";
        echo "<strong>Current (En Curso):</strong> " . ($isCurrent ? 'YES' : 'NO') . "<br>";
        echo "<strong>Future (Próximos):</strong> " . ($isFuture ? 'YES' : 'NO') . "<br>";
        echo "Start > Now: " . ($startIsFuture ? 'YES' : 'NO');
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
});

// Rutas de Autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Gestión Académica
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::patch('/courses/{id}/terminate', [CourseController::class, 'terminate'])->name('courses.terminate');

    // Gestión de Programas (sesiones de cursos)
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

    // Rutas Relatores (Teachers - Legacy)
    // Route::resource('teachers', TeacherController::class); // Se mantiene si es necesario por retrocompatibilidad momentanea

    // Rutas Relatores (Nueva Implementación)
    Route::resource('relators', \App\Http\Controllers\Admin\RelatorController::class);
    Route::get('/teachers', [\App\Http\Controllers\Admin\RelatorController::class, 'index'])->name('teachers.index'); // Redirección temporal de nombres de ruta comunes si se usan en layouts
    Route::get('/teachers/create', [\App\Http\Controllers\Admin\RelatorController::class, 'create'])->name('teachers.create');


    Route::get('/assessments', [AssessmentController::class, 'index'])->name('assessments.index');

    // Gestión de Eventos
    Route::get('/events', [EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
    Route::post('/events', [EventController::class, 'store'])->name('events.store');
    Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');
    Route::get('/events/{id}/edit', [EventController::class, 'edit'])->name('events.edit');
    Route::put('/events/{id}', [EventController::class, 'update'])->name('events.update');
    Route::patch('/events/{id}/toggle', [EventController::class, 'toggleStatus'])->name('events.toggle');

    // Otras rutas
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');

    // Administración del Sistema
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/resend', [UserController::class, 'resendCredentials'])->name('users.resend');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

    Route::post('/users/mass-destroy', [UserController::class, 'massDestroy'])->name('users.mass_destroy');
    Route::get('/certificates/download/{login}/{course}', [CertificateController::class, 'download'])->name('certificates.download');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Gestión de Categorías
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('/categorias/crear', [App\Http\Controllers\CategoriaController::class, 'create'])->name('categorias.create');
    Route::post('/categorias', [App\Http\Controllers\CategoriaController::class, 'store'])->name('categorias.store');
    Route::get('/categorias/{id}/editar', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('categorias.edit');
    Route::put('/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('categorias.update');
    Route::delete('/categorias/{id}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('categorias.destroy');

    // Gestión de Certificados (Plantillas)
    Route::match(['get', 'post'], '/certificates/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
    Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
    Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
    Route::get('/certificates/{id}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
    Route::put('/certificates/{id}', [CertificateController::class, 'update'])->name('certificates.update');
    Route::delete('/certificates/{id}', [CertificateController::class, 'destroy'])->name('certificates.destroy');




    // Portal del Relator
    Route::prefix('relator')->name('relator.')->group(function () {
        Route::get('/my-courses', [App\Http\Controllers\Admin\RelatorPortalController::class, 'index'])->name('my_courses');
        Route::get('/course/{id}/students', [App\Http\Controllers\Admin\RelatorPortalController::class, 'students'])->name('course_students');
        Route::post('/approval/{id}', [App\Http\Controllers\Admin\RelatorPortalController::class, 'toggleApproval'])->name('toggle_approval');
    });

});

// ==========================================
// PORTAL DEL PARTICIPANTE
// ==========================================
Route::prefix('portal')->name('participant.')->group(function () {

    // Autenticación
    Route::get('login', [App\Http\Controllers\Participant\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [App\Http\Controllers\Participant\Auth\LoginController::class, 'login']);
    Route::post('logout', [App\Http\Controllers\Participant\Auth\LoginController::class, 'logout'])->name('logout');

    // Dashboard Protegido
    Route::middleware('auth:participant')->group(function () {
        Route::get('/', [App\Http\Controllers\Participant\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/mis-cursos', [App\Http\Controllers\Participant\DashboardController::class, 'myCourses'])->name('my_courses');
        Route::get('/agenda', [App\Http\Controllers\Participant\DashboardController::class, 'agenda'])->name('agenda');
        Route::get('/perfil', [App\Http\Controllers\Participant\ProfileController::class, 'index'])->name('profile');
        Route::get('/seguridad', [App\Http\Controllers\Participant\ProfileController::class, 'security'])->name('security');
        Route::post('/perfil/password', [App\Http\Controllers\Participant\ProfileController::class, 'updatePassword'])->name('update_password');

        // Ayuda
        Route::get('/ayuda', [App\Http\Controllers\Participant\ContactController::class, 'show'])->name('contact');
        Route::post('/ayuda', [App\Http\Controllers\Participant\ContactController::class, 'send'])->name('contact.send');

        // Inscripción
        Route::post('/inscribirse/{course}', [App\Http\Controllers\Participant\EnrollmentController::class, 'store'])->name('enroll');

        // Certificados
        Route::get('/mis-cursos/certificado/{course}', [App\Http\Controllers\Participant\CertificateController::class, 'download'])->name('certificates.download');

        // Portal Relator (Dentro del área de participante)
        Route::prefix('relator')->name('relator.')->group(function () {
            Route::get('/mis-cursos-docente', [App\Http\Controllers\Participant\RelatorController::class, 'index'])->name('my_courses');
            Route::get('/crear-curso', [App\Http\Controllers\Participant\RelatorController::class, 'create'])->name('create_course');
            Route::post('/crear-curso', [App\Http\Controllers\Participant\RelatorController::class, 'store'])->name('store_course');
            Route::get('/curso-docente/{id}/alumnos', [App\Http\Controllers\Participant\RelatorController::class, 'students'])->name('course_students');
            Route::post('/aprobacion/{id}', [App\Http\Controllers\Participant\RelatorController::class, 'toggleApproval'])->name('toggle_approval');
        });
    });

    Route::get('/debug-schema', function () {
        try {
            return response()->json(\Illuminate\Support\Facades\DB::select('DESCRIBE inscripcion'));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    });

});
