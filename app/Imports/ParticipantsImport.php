<?php

namespace App\Imports;

use App\Models\Participante;
use App\Models\Inscripcion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ParticipantsImport implements ToCollection, WithHeadingRow
{
    protected $programId;
    protected $courseId;

    public $importedCount = 0;
    public $updatedCount = 0;
    public $failedCount = 0;

    public function __construct($programId, $courseId)
    {
        $this->programId = $programId;
        $this->courseId = $courseId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Normalizar keys
            $row = $row->mapWithKeys(function ($item, $key) {
                return [Str::slug($key, '_') => $item];
            });

            // Detectar email en varios campos posibles
            $email = $row['correo'] ?? $row['email'] ?? $row['mail'] ?? $row['correo_electronico'] ?? null;

            if (!$email) {
                // Log::warning('Fila sin correo: ' . json_encode($row));
                continue;
            }
            $email = trim($email);

            $nombre = $row['nombres'] ?? $row['nombre'] ?? '';
            $apellido = $row['apellidos'] ?? $row['apellido'] ?? '';
            $rut = $row['rut'] ?? $row['run'] ?? null;

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
                    $usuario->par_perfil = 'par'; // Participante por defecto
                    $usuario->par_cargo = 'Participante Externo';
                    $usuario->par_anexo = 'N/A';
                    $usuario->fecha_registro = now();
                    $usuario->save();
                    // $this->importedCount++; // Se cuenta como importado al inscribir
                } else {
                    // Actualizar
                    if ($nombre)
                        $usuario->par_nombre = $nombre;
                    if ($apellido)
                        $usuario->par_apellido = $apellido;
                    $usuario->save();
                    $this->updatedCount++;
                }

                // 2. Inscribir
                $inscripcion = Inscripcion::where('cur_id', $this->courseId)
                    ->where('par_login', $usuario->par_login)
                    ->first();

                if (!$inscripcion) {
                    $inscripcion = new Inscripcion();
                    $inscripcion->cur_id = $this->courseId;
                    $inscripcion->pro_id = $this->programId;
                    $inscripcion->par_login = $usuario->par_login;
                    $inscripcion->ins_date = now();
                    $inscripcion->save();
                    $this->importedCount++;
                }
            } catch (\Exception $e) {
                $this->failedCount++;
                // Log::error("Error importando $email: " . $e->getMessage());
            }
        }
    }
}
