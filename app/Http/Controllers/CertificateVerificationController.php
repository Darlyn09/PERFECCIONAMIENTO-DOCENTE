<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\User;
use App\Models\Inscripcion;
use Illuminate\Http\Request;

class CertificateVerificationController extends Controller
{
    /**
     * Verificar la validez de un certificado mediante URL firmada.
     */
    public function verify(Request $request, $userLogin, $courseId)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Enlace de verificación inválido o expirado.');
        }

        $participant = \App\Models\Participante::where('par_login', $userLogin)->firstOrFail();
        $course = Curso::findOrFail($courseId);

        // 2. Verificar que el usuario realmente aprobó el curso
        $inscripcion = Inscripcion::where('par_login', $userLogin)
            ->where('cur_id', $courseId)
            ->with('informacion')
            ->first();

        $isApproved = false;
        $completionDate = null;

        if ($inscripcion && $inscripcion->isApproved()) {
            $isApproved = true;
            $completionDate = $inscripcion->curso->cur_fecha_termino; // O fecha certificado
        }

        if (!$isApproved) {
            return view('certificates.validate', [
                'isValid' => false,
                'message' => 'El certificado no es válido. El participante no ha aprobado este curso.'
            ]);
        }

        return view('certificates.validate', [
            'isValid' => true,
            'participant' => $participant,
            'course' => $course,
            'date' => $completionDate,
            'validationCode' => 'VERIFICADO-LINK' // Indicador de que es por link
        ]);
    }

    /**
     * Valida el certificado mediante ID de inscripción y Hash único.
     */
    public function validateCertificate($id, $hash)
    {
        // 1. Recrear el hash esperado
        // Usamos una salt secreta (en producción debería estar en env)
        $salt = 'CERTIFICATE_VALIDATION_SECRET_KEY_2024';
        $expectedHash = substr(md5($id . $salt), 0, 8); // 8 caracteres son suficientes para este propósito

        if ($hash !== $expectedHash) {
            return view('certificates.validate', [
                'isValid' => false,
                'message' => 'Código de validación inválido.'
            ]);
        }

        // 2. Buscar la inscripción
        $inscripcion = Inscripcion::with(['participante', 'curso', 'informacion'])->find($id);

        if (!$inscripcion) {
            return view('certificates.validate', [
                'isValid' => false,
                'message' => 'Certificado no encontrado.'
            ]);
        }

        // 3. Verificar estado de aprobación
        $isApproved = $inscripcion->isApproved();

        if (!$isApproved) {
            return view('certificates.validate', [
                'isValid' => false,
                'message' => 'El documento no corresponde a un certificado válido (Curso no aprobado).'
            ]);
        }

        // 4. Retornar vista de éxito
        return view('certificates.validate', [
            'isValid' => true,
            'inscription' => $inscripcion,
            'participant' => $inscripcion->participante,
            'course' => $inscripcion->curso,
            'date' => $inscripcion->curso->cur_fecha_termino,
            'validationCode' => strtoupper($hash . '-' . $id)
        ]);
    }
}
