<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'evento';
    protected $primaryKey = 'eve_id';
    public $timestamps = false;

    protected $fillable = [
        'eve_nombre',
        'eve_descripcion',
        'eve_inicia',
        'eve_finaliza',
        'eve_abre',
        'eve_cierra',
        'eve_tipo',
        'eve_estado',
        'eve_imagen',
    ];

    /**
     * Relación: Un evento tiene muchos cursos
     */
    public function cursos()
    {
        return $this->hasMany(Curso::class, 'eve_id', 'eve_id');
    }

    /**
     * Contar cursos activos del evento
     */
    public function cursosActivos()
    {
        return $this->cursos()->where('cur_estado', 1);
    }
}
