<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'facultad';
    
    // Descomentar si no se usan timestamps (created_at, updated_at)
    // public $timestamps = false;
}
