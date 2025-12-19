<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relator extends Model
{
    use HasFactory;

    protected $table = 'relator';
    protected $primaryKey = 'rel_login';
    public $incrementing = false; // PK es varchar
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'rel_login',
        'rel_nombre',
        'rel_apellido',
        'rel_correo',
        'rel_facultad',
        'rel_fono',
        'rel_cargo',
        'rel_estado',
        'rel_tipo', // 1 = Interno, 2 = Externo
    ];

    /**
     * Relación: Un relator tiene muchos programas (sesiones) - COMO TITULAR
     */
    public function programas()
    {
        return $this->hasMany(Programa::class, 'rel_login', 'rel_login');
    }

    /**
     * Relación: Un relator participa en muchos programas - COMO COLABORADOR/ASIGNADO (Req 59)
     */
    public function programasAsignados()
    {
        return $this->belongsToMany(Programa::class, 'programa_relator', 'rel_login', 'pro_id')
            ->withTimestamps();
    }

    /**
     * Obtener los cursos únicos que ha dictado el relator
     */
    public function cursos()
    {
        return Curso::whereHas('programas', function ($query) {
            $query->where('rel_login', $this->rel_login);
        });
    }

    /**
     * Determinar si es interno o externo
     */
    public function getTipoNombreAttribute()
    {
        // Si tiene rel_tipo definido, usarlo
        if (isset($this->rel_tipo)) {
            return $this->rel_tipo == 1 ? 'Interno' : 'Externo';
        }
        // Si no, determinarlo por facultad
        return $this->rel_facultad ? 'Interno' : 'Externo';
    }

    /**
     * Determinar si es interno
     */
    public function getEsInternoAttribute()
    {
        if (isset($this->rel_tipo)) {
            return $this->rel_tipo == 1;
        }
        return !empty($this->rel_facultad);
    }
}
