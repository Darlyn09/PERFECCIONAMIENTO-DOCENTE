<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informacion extends Model
{
    use HasFactory;

    protected $table = 'informacion';
    protected $primaryKey = 'ins_id';
    public $timestamps = false; // No timestamps evident in schema

    protected $fillable = [
        'ins_id',
        'inf_situacion',
        'inf_asistencia',
        'inf_comentario',
        'inf_estado', // 1 = Aprobado
    ];

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'ins_id', 'ins_id');
    }
}
