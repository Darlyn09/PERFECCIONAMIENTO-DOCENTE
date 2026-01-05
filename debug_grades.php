<?php

use App\Models\Participante;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;

// Fetch a user (any user)
$user = Participante::first();

if (!$user) {
    echo "No users found.\n";
    exit;
}

echo "User: {$user->par_login}\n";

// Run the exact query from UserController (simplified)
$inscripciones = Inscripcion::join('curso', 'inscripcion.cur_id', '=', 'curso.cur_id')
    ->leftJoin('informacion', 'inscripcion.ins_id', '=', 'informacion.ins_id')
    ->where('inscripcion.par_login', $user->par_login)
    ->select(
        'inscripcion.ins_id',
        'curso.cur_nombre',
        'informacion.inf_id',
        'informacion.inf_nota',
        'informacion.inf_estado'
    )
    ->get();

if ($inscripciones->isEmpty()) {
    echo "No enrollments for this user.\n";
} else {
    foreach ($inscripciones as $ins) {
        echo "Inscripcion ID: {$ins->ins_id} | Curso: {$ins->cur_nombre} | Info ID: {$ins->inf_id} | Nota: " . var_export($ins->inf_nota, true) . " | Estado: {$ins->inf_estado}\n";
    }
}

// Check raw Informacion table
echo "\n--- Raw Informacion Table (First 5) ---\n";
$infos = DB::table('informacion')->limit(5)->get();
foreach ($infos as $info) {
    echo "Ins ID: {$info->ins_id} | Nota: {$info->inf_nota}\n";
}
