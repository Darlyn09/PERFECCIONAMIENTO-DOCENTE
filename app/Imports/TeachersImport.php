<?php

namespace App\Imports;

use App\Models\Relator;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class TeachersImport implements ToCollection, WithHeadingRow
{
    protected $programId;

    public $importedCount = 0;
    public $updatedCount = 0;
    public $failedCount = 0;

    public function __construct($programId = null)
    {
        $this->programId = $programId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalizar keys
            $row = $row->mapWithKeys(function ($item, $key) {
                return [Str::slug($key, '_') => $item];
            });

            // Detectar email
            $email = $row['correo'] ?? $row['email'] ?? $row['mail'] ?? $row['correo_electronico'] ?? null;

            if (!$email) {
                continue;
            }
            $email = trim($email);

            $nombre = $row['nombres'] ?? $row['nombre'] ?? '';
            $apellido = $row['apellidos'] ?? $row['apellido'] ?? '';
            $rut = $row['rut'] ?? $row['run'] ?? $row['login'] ?? null;

            // Datos extra
            $cargo = $row['cargo'] ?? null;
            $facultad = $row['facultad'] ?? $row['unidad'] ?? null;
            $fono = $row['fono'] ?? $row['telefono'] ?? null;

            if ($rut)
                $rut = trim($rut);
            if ($nombre)
                $nombre = trim($nombre);
            if ($apellido)
                $apellido = trim($apellido);

            try {
                // 1. Buscar Relator
                $query = Relator::where('rel_correo', $email);
                if ($rut) {
                    $query->orWhere('rel_login', $rut);
                }
                $relator = $query->first();

                if (!$relator) {
                    // Crear Relator
                    $relator = new Relator();
                    $relator->rel_login = $rut ?: $email; // Usar RUT o Email como login
                    $relator->rel_nombre = $nombre ?: (explode('@', $email)[0]);
                    $relator->rel_apellido = $apellido ?: 'Importado';
                    $relator->rel_correo = $email;
                    $relator->rel_cargo = $cargo;
                    $relator->rel_facultad = $facultad;
                    $relator->rel_fono = $fono;
                    $relator->rel_estado = 1; // Activo por defecto
                    $relator->save();
                    $this->importedCount++;
                } else {
                    // Actualizar
                    if ($nombre)
                        $relator->rel_nombre = $nombre;
                    if ($apellido)
                        $relator->rel_apellido = $apellido;
                    if ($cargo)
                        $relator->rel_cargo = $cargo;
                    if ($facultad)
                        $relator->rel_facultad = $facultad;
                    if ($fono)
                        $relator->rel_fono = $fono;
                    $relator->save();
                    $this->updatedCount++;
                    $this->updatedCount++;
                }

                // Asignar al programa si existe $programId
                if ($this->programId && $relator) {
                    // Verificar si ya está asignado para no duplicar (aunque sync without detaching podría servir, pero attach es más simple)
                    // Asumimos relación muchos a muchos: programa_relator
                    // Usamos la relación 'programasAsignados' definida en Relator o 'relatores' en Programa.
                    // Vamos a hacerlo directo con DB o modelo si existe relación.
                    // Relator tiene 'programasAsignados'.

                    if (!$relator->programasAsignados()->where('programa.pro_id', $this->programId)->exists()) {
                        $relator->programasAsignados()->attach($this->programId);
                    }
                }

            } catch (\Exception $e) {
                $this->failedCount++;
            }
        }
    }
}
