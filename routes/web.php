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

// Ruta de verificación eliminada para producción


// Rutas de Autenticación
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::any('/certificates/preview', [CertificateController::class, 'preview'])->name('certificates.preview');
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Gestión Académica
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{id}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{id}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->name('courses.update');
    Route::patch('/courses/{id}/terminate', [CourseController::class, 'terminate'])->name('courses.terminate');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Gestión de Programas (sesiones de cursos)
    Route::get('/offerings', [ProgramController::class, 'indexGlobal'])->name('offerings.index');
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::patch('/programs/{id}/toggle', [ProgramController::class, 'toggleStatus'])->name('programs.toggle');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    Route::get('/programs/{id}/export', [ProgramController::class, 'exportParticipants'])->name('programs.export');

    // Gestión Docentes Programa
    Route::post('/programs/{id}/import-participants', [ProgramController::class, 'importParticipants'])->name('programs.import_participants');
    Route::get('/programs/{id}/teachers', [ProgramController::class, 'teachers'])->name('programs.teachers');
    Route::post('/programs/{id}/assign-teacher', [ProgramController::class, 'assignTeacher'])->name('programs.assign_teacher');
    Route::delete('/programs/{id}/detach-teacher/{relLogin}', [ProgramController::class, 'detachTeacher'])->name('programs.detach_teacher');
    Route::post('/programs/{id}/import-teachers', [ProgramController::class, 'importTeachers'])->name('programs.import_teachers');

    // Gestión Calificaciones Programa
    Route::get('/programs/{id}/grades', [ProgramController::class, 'grades'])->name('programs.grades');
    Route::post('/programs/{id}/update-grades', [ProgramController::class, 'updateGrades'])->name('programs.update_grades');
    Route::post('/programs/{id}/import-grades', [ProgramController::class, 'importGrades'])->name('programs.import_grades');
    Route::get('/programs/{id}/grades-template', [ProgramController::class, 'downloadGradesTemplate'])->name('programs.grades_template');

    // Gestión Calificaciones Relatores (Nueva)
    Route::get('/programs/{id}/relator-grades', [ProgramController::class, 'relatorGrades'])->name('programs.relator_grades');
    Route::post('/programs/{id}/update-relator-grades', [ProgramController::class, 'updateRelatorGrades'])->name('programs.update_relator_grades');
    Route::post('/programs/{id}/certify-teacher/{relLogin}', [ProgramController::class, 'certifyTeacher'])->name('programs.certify_teacher');
    Route::get('/programs/{id}/relator-certificate/{relLogin}', [CertificateController::class, 'downloadRelatorCertificate'])->name('relators.certificate');


    // Rutas Relatores (Teachers - Legacy)
    // Route::resource('teachers', TeacherController::class); // Se mantiene si es necesario por retrocompatibilidad momentanea

    // Rutas Relatores (Nueva Implementación)
    // Gestión de Relatores
    Route::post('/relators/mass-destroy', [\App\Http\Controllers\Admin\RelatorController::class, 'massDestroy'])->name('relators.mass_destroy');
    Route::get('/relators/export', [\App\Http\Controllers\Admin\RelatorController::class, 'export'])->name('relators.export');
    Route::get('/relators/search', [\App\Http\Controllers\Admin\RelatorController::class, 'searchRelator'])->name('relators.search'); // New Search
    Route::post('/relators/import', [\App\Http\Controllers\Admin\RelatorController::class, 'import'])->name('relators.import'); // New Import
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
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::patch('/events/{id}/toggle', [EventController::class, 'toggleStatus'])->name('events.toggle');

    // Otras rutas
    Route::get('/schedules', [ScheduleController::class, 'index'])->name('schedules.index');
    Route::get('/participants', [ParticipantController::class, 'index'])->name('participants.index');

    // Gestión de Usuarios
    Route::post('/users/{login}/password', [UserController::class, 'updatePassword'])->name('users.update_password'); // Ruta correcta para update password
    Route::post('/users/mass-destroy', [UserController::class, 'massDestroy'])->name('users.mass_destroy'); // Nueva ruta
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export'); // Nueva ruta export
    Route::get('/users/search', [UserController::class, 'searchByRut'])->name('users.search'); // Nueva ruta search
    Route::post('/users/import', [UserController::class, 'import'])->name('users.import'); // Nueva ruta import
    Route::resource('users', UserController::class)->parameter('user', 'id'); // 'id' para que coincida con lo típico, pero usaremos par_login en controller
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{id}/resend', [UserController::class, 'resendCredentials'])->name('users.resend');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');

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
    // Route::match(['get', 'post'], '/certificates/preview', [CertificateController::class, 'preview'])->name('certificates.preview'); // Moved to top
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
        Route::get('/mis-certificados', [App\Http\Controllers\Participant\DashboardController::class, 'myCertificates'])->name('certificates');
        Route::get('/agenda', [App\Http\Controllers\Participant\DashboardController::class, 'agenda'])->name('agenda');
        Route::get('/perfil', [App\Http\Controllers\Participant\ProfileController::class, 'index'])->name('profile');
        Route::get('/seguridad', [App\Http\Controllers\Participant\ProfileController::class, 'security'])->name('security');
        Route::post('/perfil/password', [App\Http\Controllers\Participant\ProfileController::class, 'updatePassword'])->name('update_password');

        // Ayuda
        Route::get('/ayuda', [App\Http\Controllers\Participant\ContactController::class, 'show'])->name('contact');
        Route::post('/ayuda', [App\Http\Controllers\Participant\ContactController::class, 'send'])->name('contact.send');

        // Inscripción
        Route::post('/inscribirse/{course}', [App\Http\Controllers\Participant\EnrollmentController::class, 'store'])->name('enroll');

        // Catálogo de Cursos (Req 74-80)
        Route::get('/catalogo', [App\Http\Controllers\Participant\CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalogo/{id}', [App\Http\Controllers\Participant\CatalogController::class, 'show'])->name('catalog.show');
        Route::post('/catalogo/{id}/inscribir', [App\Http\Controllers\Participant\CatalogController::class, 'enroll'])->name('catalog.enroll');
        Route::delete('/catalogo/{id}/cancelar', [App\Http\Controllers\Participant\CatalogController::class, 'cancel'])->name('catalog.cancel');

        // Certificados
        // Certificados
        Route::get('/certificate/{login}/{courseId}', [\App\Http\Controllers\Admin\CertificateController::class, 'download'])->name('certificates.download');
        Route::post('/rate-course', [\App\Http\Controllers\Participant\DashboardController::class, 'rateCourse'])->name('courses.rate');
        Route::post('/feedback/{id}', [App\Http\Controllers\Participant\DashboardController::class, 'saveFeedback'])->name('save_feedback');

        // Portal Relator (Dentro del área de participante)
        Route::prefix('relator')->name('relator.')->group(function () {
            Route::get('/mis-cursos-docente', [App\Http\Controllers\Participant\RelatorController::class, 'index'])->name('my_courses');
            Route::get('/crear-curso', [App\Http\Controllers\Participant\RelatorController::class, 'create'])->name('create_course');
            Route::post('/crear-curso', [App\Http\Controllers\Participant\RelatorController::class, 'store'])->name('store_course');
            Route::get('/curso-docente/{id}/alumnos', [App\Http\Controllers\Participant\RelatorController::class, 'students'])->name('course_students');
            Route::get('/programa-docente/{id}/calificaciones', [App\Http\Controllers\Participant\RelatorController::class, 'grades'])->name('program_grades');
            Route::post('/programa-docente/{id}/calificaciones', [App\Http\Controllers\Participant\RelatorController::class, 'updateGrades'])->name('update_grades');
            Route::get('/programa-docente/{id}/certificado', [App\Http\Controllers\Participant\RelatorController::class, 'downloadCertificate'])->name('certificate');
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
