<?php

namespace App\Imports;

use App\Models\Participante;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersImport implements ToCollection, WithHeadingRow
{
    public $importedCount = 0;
    public $updatedCount = 0;
    public $failedCount = 0;

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
            $perfil = $row['perfil'] ?? 'par';

            if ($rut)
                $rut = trim($rut);
            if ($nombre)
                $nombre = trim($nombre);
            if ($apellido)
                $apellido = trim($apellido);

            try {
                // 1. Buscar Usuario
                $query = Participante::where('par_correo', $email);
                if ($rut) {
                    $query->orWhere('par_login', $rut);
                }
                $usuario = $query->first();

                if (!$usuario) {
                    // Crear Usuario
                    $usuario = new Participante();
                    $usuario->par_login = $rut ?: $email;
                    $usuario->par_nombre = $nombre ?: (explode('@', $email)[0]);
                    $usuario->par_apellido = $apellido ?: 'Importado';
                    $usuario->par_correo = $email;
                    $usuario->par_password = Hash::make($email);
                    $usuario->par_perfil = $perfil;
                    $usuario->par_cargo = $cargo ?: 'Participante';
                    $usuario->par_facultad = $facultad;
                    $usuario->par_anexo = $fono;
                    $usuario->fecha_registro = now();
                    $usuario->save();
                    $this->importedCount++;
                } else {
                    // Actualizar
                    if ($nombre)
                        $usuario->par_nombre = $nombre;
                    if ($apellido)
                        $usuario->par_apellido = $apellido;
                    if ($cargo)
                        $usuario->par_cargo = $cargo;
                    if ($facultad)
                        $usuario->par_facultad = $facultad;
                    $usuario->save();
                    $this->updatedCount++;
                }
            } catch (\Exception $e) {
                $this->failedCount++;
            }
        }
    }
}
