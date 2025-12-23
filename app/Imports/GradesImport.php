<?php

namespace App\Imports;

use App\Models\Inscripcion;
use App\Models\Programa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class GradesImport implements ToCollection
{
    protected $programaId;
    public $updatedCount = 0;
    public $failedCount = 0;

    public function __construct($programaId)
    {
        $this->programaId = $programaId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Omitir cabecera (fila 0) si detectamos texto común
            if ($index === 0 && (str_contains(strtoupper($row[0]), 'RUT') || str_contains(strtoupper($row[0]), 'LOGIN'))) {
                continue;
            }

            // Mapeo basado en lógica CSV anterior: 
            // 0: RUT, 1: Nombre (ignorar), 2: Nota, 3: Asistencia
            $rut = trim($row[0]);

            // Validar RUT vacío
            if (empty($rut))
                continue;

            // Normalización de nota (coma a punto)
            $notaRaw = $row[2] ?? null;
            $nota = $notaRaw ? str_replace(',', '.', trim($notaRaw)) : null;

            $asistencia = isset($row[3]) ? trim($row[3]) : null;

            // Buscar inscripción
            $inscripcion = Inscripcion::where('pro_id', $this->programaId)
                ->whereHas('participante', function ($q) use ($rut) {
                    $q->where('par_rut', $rut)->orWhere('par_login', $rut);
                })->first();

            if ($inscripcion) {
                if ($nota !== null)
                    $inscripcion->ins_nota = $nota;
                if ($asistencia !== null)
                    $inscripcion->ins_asistencia = $asistencia;

                // Solo guardar si hubo cambios (o forzar update)
                // Aquí guardamos siempre para asegurar
                $inscripcion->save();
                $this->updatedCount++;
            } else {
                $this->failedCount++;
            }
        }
    }
}
