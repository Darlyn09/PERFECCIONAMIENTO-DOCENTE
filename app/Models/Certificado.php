<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado extends Model
{
    use HasFactory;

    protected $table = 'certificados';

    protected $fillable = [
        'nombre',
        'tipo',
        'referencia_id',
        'configuracion',
        'imagen_fondo',
        'firma_imagen',
        'width',
        'height',
        'page_size',
        'orientation',
    ];

    protected $casts = [
        'configuracion' => 'array',
    ];

    // Helper to get configuration values with defaults
    public function getConfig($key, $default = null)
    {
        return ($this->configuracion ?? [])[$key] ?? $default;
    }

    // Relationships can be defined here if we enforce referential integrity locally
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'referencia_id', 'cur_id');
    }
}
