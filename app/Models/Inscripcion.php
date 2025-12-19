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
}
