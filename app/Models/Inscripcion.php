<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcion';
    protected $primaryKey = 'ins_id';
    public $timestamps = false;

    protected $fillable = [
        'par_login',
        'cur_id',
        'pro_id',
        'ins_perfil',
        'ins_udec',
        'ins_date'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cur_id', 'cur_id');
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class, 'par_login', 'par_login');
    }

    public function informacion()
    {
        // ins_id en informacion es FK (según deducción), o quizá PK compartida
        // Asumimos hasOne por ins_id
        return $this->hasOne(Informacion::class, 'ins_id', 'ins_id');
    }

    /**
     * Determina si la inscripción está aprobada.
     * Criterio: Nota >= 4.0 O Estado == 1 (Legacy)
     */
    public function isApproved()
    {
        // 1. Intentar obtener datos de relación o atributos directos
        $notaRaw = $this->inf_nota ?? ($this->informacion->inf_nota ?? null);
        $estado = $this->inf_estado ?? ($this->informacion->inf_estado ?? 0);

        // 2. Verificar Nota
        $notaStr = str_replace(',', '.', $notaRaw);
        $porNota = is_numeric($notaStr) && floatval($notaStr) >= 4.0;

        // 3. Verificar Estado (Legacy)
        $porEstado = $estado == 1;

        return $porNota || $porEstado;
    }
}
