<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $primaryKey = 'cur_categoria';
    public $timestamps = false;

    protected $fillable = [
        'nom_categoria',
    ];

    public function cursos()
    {
        return $this->hasMany(Curso::class, 'cur_categoria', 'cur_categoria');
    }
}
