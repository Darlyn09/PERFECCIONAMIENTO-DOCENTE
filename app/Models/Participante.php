<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Participante extends Authenticatable
{
    use HasFactory;

    protected $table = 'participante';

    protected $primaryKey = 'par_login';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'par_login',
        'par_nombre',
        'par_apellido',
        'par_correo',
        'par_password',
        'par_perfil',
        'par_facultad',
        'par_departamento',
        'par_sede',

        'fecha_registro',
        'last_login_at'
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function getAuthPassword()
    {
        return $this->par_password;
    }

    // Relación con Inscripciones (Cursos)
    public function inscripciones()
    {
        return $this->hasMany(Inscripcion::class, 'par_login', 'par_login');
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'inscripcion', 'par_login', 'cur_id');
    }

    // Relación con Eventos
    public function participaciones()
    {
        return $this->hasMany(Participacion::class, 'par_login', 'par_login');
    }

    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'participacion', 'par_login', 'eve_id');
    }

    // Relación con Relator
    public function relator()
    {
        return $this->hasOne(Relator::class, 'rel_login', 'par_login');
    }
}
