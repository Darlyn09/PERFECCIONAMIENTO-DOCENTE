<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Administrador extends Authenticatable
{
    use HasFactory;

    protected $table = 'administrador';
    protected $primaryKey = 'adm_login'; // Asumiendo adm_login como identificador único si no hay ID numérico visible
    public $incrementing = false; // Si es string
    protected $keyType = 'string';

    // Deshabilitar timestamps si la tabla no los tiene
    public $timestamps = false;

    public function getAuthPassword()
    {
        return $this->adm_password;
    }

    public function getAuthIdentifierName()
    {
        return 'adm_login';
    }

    public function relator()
    {
        return $this->hasOne(Relator::class, 'rel_login', 'adm_login');
    }
}
