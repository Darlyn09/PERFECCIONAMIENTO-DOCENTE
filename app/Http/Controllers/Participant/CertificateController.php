<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\Curso;
use App\Models\Inscripcion;

class CertificateController extends Controller
{
    private string $jsonPath = 'certificates.json';

    /* =====================================================
     |  Helpers
     ===================================================== */

    private function getCertificates(): array
    {
        if (!Storage::exists($this->jsonPath)) {
            return [];
        }

        return json_decode(Storage::get($this->jsonPath), true) ?? [];
    }

    private function generateHtmlCss(array $data): array
    {
        $settings = $data['settings'] ?? [];

        $params = [
            'bg_color' => $settings['bg_color'] ?? '#ffffff',
            'border_color' => $settings['border_color'] ?? '#dddddd',
            'border_width' => $settings['border_width'] ?? 10,
            'title_text' => $settings['title_text'] ?? 'Certificado',
            'title_color' => $settings['title_color'] ?? '#333333',
            'title_size' => $settings['title_size'] ?? 40,
            'title_font' => $settings['title_font'] ?? "'Arial', sans-serif",
            'title_align' => $settings['title_align'] ?? 'center',
            'title_margin' => $settings['title_margin'] ?? 40,
            'body_text' => $settings['body_text'] ?? 'Otorgado a:',
            'body_color' => $settings['body_color'] ?? '#666666',
            'body_size' => $settings['body_size'] ?? 20,
            'body_font' => $settings['body_font'] ?? "'Arial', sans-serif",
            'secondary_text' => $settings['secondary_text'] ?? 'Por aprobar el curso:',
            'date_text' => $settings['date_text'] ?? 'Fecha:',
            'student_weight' => $settings['student_weight'] ?? 'bold',
            'student_margin' => $settings['student_margin'] ?? 20,
            'course_margin' => $settings['course_margin'] ?? 20,
            'date_margin' => $settings['date_margin'] ?? 40,
            'signature_text' => $settings['signature_text'] ?? 'Firma Autorizada',
            'signature_margin' => $settings['signature_margin'] ?? 40,
            'show_qr' => filter_var($settings['show_qr'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'qr_position' => $settings['qr_position'] ?? 'left',
            'qr_size' => $settings['qr_size'] ?? 150,
        ];

        /* ================= Background ================= */

        $bgCss = !empty($data['bg_image_url'])
            ? "background-image: url('{$data['bg_image_url']}'); background-size: cover; background-position: center;"
            : "background-color: {$params['bg_color']};";

        /* ================= QR ================= */

        $qrHtml = '';
        if ($params['show_qr']) {

            $verifyUrl = $data['verification_url']
                ?? config('app.url') . route('certificates.validate', [], false);

            $qrApi = "https://quickchart.io/qr?text=" . urlencode($verifyUrl) . "&size=400";

            try {
                $qrContent = Http::get($qrApi)->body();
                $qrBase64 = 'data:image/png;base64,' . base64_encode($qrContent);
            } catch (\Throwable $e) {
                $qrBase64 = $qrApi;
            }

            $qrStyle = "position:absolute; bottom:60px; width:{$params['qr_size']}px; height:{$params['qr_size']}px;";
            $qrStyle .= $params['qr_position'] === 'right' ? 'right:60px;' : 'left:60px;';

            $qrHtml = "
                <div style='{$qrStyle}'>
                    <img src='{$qrBase64}' style='width:100%;height:100%;'>
                </div>
            ";
        }

        /* ================= CSS ================= */

        $css = "
            .cert-container{
                width:100%;
                height:100%;
                {$bgCss}
                border:{$params['border_width']}px solid {$params['border_color']};
                box-sizing:border-box;
                padding:40px;
                text-align:center;
                position:relative;
                font-family:{$params['body_font']};
            }
            .cert-title{
                font-size:{$params['title_size']}px;
                color:{$params['title_color']};
                font-family:{$params['title_font']};
                margin-top:{$params['title_margin']}px;
                font-weight:bold;
            }
            .cert-student{
                font-size:" . ($params['body_size'] * 1.5) . "px;
                font-weight:{$params['student_weight']};
                margin-top:{$params['student_margin']}px;
            }
            .cert-course{
                font-size:" . ($params['body_size'] * 1.25) . "px;
                margin-top:{$params['course_margin']}px;
                font-weight:bold;
            }
            .cert-date{
                margin-top:{$params['date_margin']}px;
                color:#666;
            }
            .cert-signature{
                margin-top:{$params['signature_margin']}px;
            }
            .cert-signature-line{
                width:200px;
                border-top:1px solid #333;
                margin:10px auto;
            }
        ";

        /* ================= HTML ================= */

        $signatureHtml = '';
        if (!empty($data['signature_url'])) {
            $signatureHtml .= "<img src='{$data['signature_url']}' style='max-height:80px;'><br>";
        }
        $signatureHtml .= "
            <div class='cert-signature-line'></div>
            <div>{$params['signature_text']}</div>
        ";

        $html = "
            <div class='cert-container'>
                <div class='cert-title'>{$params['title_text']}</div>
                <div>{$params['body_text']}</div>
                <div class='cert-student'>{{nombre}}</div>
                <div style='margin-top:10px'>{$params['secondary_text']}</div>
                <div class='cert-course'>{{curso}}</div>
                <div class='cert-date'>{$params['date_text']} {{fecha}}</div>
                <div class='cert-signature'>{$signatureHtml}</div>
                {$qrHtml}
            </div>
        ";

        return compact('html', 'css');
    }

    /* =====================================================
     |  Download
     ===================================================== */

    public function download(int $courseId)
    {
        $participant = Auth::guard('participant')->user();

        $inscripcion = Inscripcion::where('par_login', $participant->par_login)
            ->where('cur_id', $courseId)
            ->with(['informacion', 'curso'])
            ->first();

        if (!$inscripcion || !$inscripcion->informacion || $inscripcion->informacion->inf_estado != 1) {
            return back()->with('error', 'No cumples los requisitos para descargar este certificado.');
        }

        $certData = collect($this->getCertificates())
            ->firstWhere('course_id', $courseId)
            ?? collect($this->getCertificates())->firstWhere('is_default', true);

        if (!$certData) {
            // Hard Fallback: Generar configuración completa por defecto
            $certData = [
                'width' => 800,
                'height' => 600,
                'name' => 'Certificado Por Defecto',
                'settings' => [
                    'bg_color' => '#ffffff',
                    'border_color' => '#1e40af', // blue-800
                    'border_width' => 10,
                    'title_text' => 'CERTIFICADO DE APROBACIÓN',
                    'title_color' => '#111827', // gray-900
                    'title_size' => 45,
                    'title_font' => "'Helvetica', sans-serif",
                    'title_margin' => 30,
                    'body_text' => 'Se otorga el presente reconocimiento a:',
                    'body_color' => '#4b5563', // gray-600
                    'body_size' => 18,
                    'body_font' => "'Helvetica', sans-serif",
                    'student_weight' => 'bold',
                    'student_margin' => 10,
                    'secondary_text' => 'Por haber aprobado el curso de capacitación:',
                    'course_margin' => 10,
                    'date_text' => 'Santiago,',
                    'date_margin' => 30,
                    'signature_text' => 'Director Académico',
                    'signature_margin' => 40,
                    'show_qr' => true,
                    'qr_size' => 150,
                    'qr_position' => 'right'
                ]
            ];
        }

        $hash = substr(md5($inscripcion->ins_id . 'CERTIFICATE_VALIDATION_SECRET_KEY_2024'), 0, 8);

        // FIX: Enforce Ngrok URL
        $publicBaseUrl = 'https://virulent-attractively-phebe.ngrok-free.dev';
        $certData['verification_url'] = $publicBaseUrl . "/certificates/validate/" . $inscripcion->ins_id . "/" . $hash;

        $generated = $this->generateHtmlCss($certData);

        // FIX: Use par_apellido (singular)
        $fullName = strtoupper($participant->par_nombre . ' ' . $participant->par_apellido);
        $courseName = strtoupper($inscripcion->curso->cur_nombre ?? 'CURSO');
        $completionDate = $inscripcion->curso->cur_fecha_termino
            ? \Carbon\Carbon::parse($inscripcion->curso->cur_fecha_termino)->format('d/m/Y')
            : now()->format('d/m/Y');

        $html = str_replace(
            ['{{nombre}}', '{{curso}}', '{{fecha}}'],
            [$fullName, $courseName, $completionDate],
            $generated['html']
        );

        return view('admin.certificates.preview', [
            'width' => $certData['width'],
            'height' => $certData['height'],
            'html' => $html,
            'css' => $generated['css']
        ]);
    }
}
