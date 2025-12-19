<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamento';
    
    // Descomentar si no se usan timestamps (created_at, updated_at)
    // public $timestamps = false;
}
