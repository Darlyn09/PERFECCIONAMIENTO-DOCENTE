<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Participante;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\DB;

echo "--- DEBUG START ---\n";

$user = Participante::whereHas('inscripciones')->first();

if (!$user) {
    echo "No users with enrollments found.\n";
    exit;
}

echo "User: " . $user->par_login . "\n";

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

foreach ($inscripciones as $ins) {
    $notaRaw = $ins->inf_nota;
    $notaStr = str_replace(',', '.', $notaRaw);
    $isNumeric = is_numeric($notaStr);
    $floatVal = floatval($notaStr);
    $aprobado = $isNumeric && $floatVal >= 4.0;

    echo "INS_ID: {$ins->ins_id}\n";
    echo "  Curso: {$ins->cur_nombre}\n";
    echo "  Inf_ID: " . ($ins->inf_id ?? 'NULL') . "\n";
    echo "  Nota Raw: " . var_export($notaRaw, true) . "\n";
    echo "  Nota Str: '$notaStr'\n";
    echo "  Is Numeric: " . ($isNumeric ? 'YES' : 'NO') . "\n";
    echo "  Float: $floatVal\n";
    echo "  Aprobado: " . ($aprobado ? 'YES' : 'NO') . "\n";
    echo "------------------\n";
}

echo "\n--- Raw Informacion Table (Limit 5) ---\n";
$infos = DB::table('informacion')->limit(5)->get();
foreach ($infos as $info) {
    echo "Ins ID: {$info->ins_id} | Nota: {$info->inf_nota}\n";
}
