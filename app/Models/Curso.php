<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use HasFactory;

    protected $table = 'curso';
    protected $primaryKey = 'cur_id';
    public $timestamps = false;

    protected $fillable = [
        'eve_id',
        'cur_nombre',
        'cur_descripcion',
        'cur_objetivos',
        'cur_contenidos',
        'cur_metodologias',
        'cur_bibliografia',
        'cur_aprobacion',
        'cur_asistencia',
        'cur_horas',
        'cur_categoria',
        'cur_modalidad',
        'cur_estado',
        'cur_fecha_inicio',
        'cur_fecha_termino',
        'cur_link',
        'cur_lugar',
        'cur_latitud',
        'cur_longitud',
    ];

    protected $casts = [
        'cur_fecha_inicio' => 'date',
        'cur_fecha_termino' => 'date',
    ];

    /**
     * Relación: Un curso pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'cur_categoria', 'cur_categoria');
    }

    /**
     * Relación: Un curso pertenece a un evento
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'eve_id', 'eve_id');
    }

    /**
     * Relación: Un curso tiene muchos programas (clases/sesiones)
     */
    public function programas()
    {
        return $this->hasMany(Programa::class, 'cur_id', 'cur_id');
    }

    /**
     * Relación: Un curso tiene muchas inscripciones
     */
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'cur_id', 'cur_id');
    }
}
