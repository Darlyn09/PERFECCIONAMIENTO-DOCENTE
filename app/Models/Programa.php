<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programa extends Model
{
    use HasFactory;

    protected $table = 'programa';
    protected $primaryKey = 'pro_id';
    public $timestamps = false;

    protected $fillable = [
        'cur_id',
        'rel_login',
        'pro_colaboradores',
        'pro_inicia',
        'pro_finaliza',
        'pro_abre',
        'pro_cierra',
        'pro_horario',
        'pro_lugar',
        'pro_cupos',
        'pro_hora_inicio',
        'pro_hora_termino',
        'pro_estado',
    ];

    /**
     * Relación: Un programa pertenece a un curso
     */
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'cur_id', 'cur_id');
    }

    /**
     * Relación: Un programa tiene un relator
     */
    public function relator()
    {
        return $this->belongsTo(Relator::class, 'rel_login', 'rel_login');
    }

    /**
     * Relación: Un programa tiene muchas inscripciones (alumnos)
     */
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'pro_id', 'pro_id');
    }

    /**
     * Relación: Múltiples docentes asignados al programa (Req 59)
     */
    public function relatores()
    {
        return $this->belongsToMany(Relator::class, 'programa_relator', 'pro_id', 'rel_login')
            ->withTimestamps();
    }
}
