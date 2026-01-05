$user = App\Models\Participante::whereHas('inscripciones')->first();

if (!$user) {
echo "No users with enrollments found.\n";
} else {
echo "User: {$user->par_login}\n";

$inscripciones = App\Models\Inscripcion::join('curso', 'inscripcion.cur_id', '=', 'curso.cur_id')
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
echo "Inscripcion ID: {$ins->ins_id} | Curso: {$ins->cur_nombre} | Info ID: {$ins->inf_id} | Nota: " .
var_export($ins->inf_nota, true) . " | Estado: {$ins->inf_estado}\n";
}
}

echo "\n--- Raw Informacion Table (Limit 5) ---\n";
$infos = Illuminate\Support\Facades\DB::table('informacion')->limit(5)->get();
foreach ($infos as $info) {
echo "Ins ID: {$info->ins_id} | Nota: {$info->inf_nota}\n";
}