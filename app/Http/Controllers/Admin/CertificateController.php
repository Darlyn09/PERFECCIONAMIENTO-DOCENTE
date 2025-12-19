<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Curso;
use App\Models\Participante;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\Http;

class CertificateController extends Controller
{
    private $jsonPath = 'certificates.json';

    private function getCertificates()
    {
        if (!Storage::exists($this->jsonPath)) {
            return [];
        }
        return json_decode(Storage::get($this->jsonPath), true) ?? [];
    }

    private function saveCertificates($certificates)
    {
        Storage::put($this->jsonPath, json_encode(array_values($certificates), JSON_PRETTY_PRINT));
    }

    private function getCourses()
    {
        return Curso::orderBy('cur_nombre')->get(['cur_id', 'cur_nombre']);
    }

    public function index()
    {
        $certificates = $this->getCertificates();
        $courseIds = array_filter(array_column($certificates, 'course_id'));
        $courses = [];
        if (!empty($courseIds)) {
            $courses = Curso::whereIn('cur_id', $courseIds)->pluck('cur_nombre', 'cur_id');
        }

        return view('admin.certificates.index', compact('certificates', 'courses'));
    }

    public function create()
    {
        // Obtener cursos que NO tienen certificado asignado aún
        $certificates = $this->getCertificates();
        $assignedCourseIds = array_column($certificates, 'course_id');

        $courses = Curso::whereNotIn('cur_id', array_filter($assignedCourseIds))
            ->orderBy('cur_nombre')
            ->get(['cur_id', 'cur_nombre']);

        return view('admin.certificates.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:curso,cur_id',
            'width' => 'nullable|integer|min:100',
            'height' => 'nullable|integer|min:100',
            'bg_color' => 'nullable|string',
            'border_color' => 'nullable|string',
            'border_width' => 'nullable|integer',
            'title_text' => 'nullable|string',
            'title_color' => 'nullable|string',
            'title_size' => 'nullable|integer',
            'body_text' => 'nullable|string',
            'body_color' => 'nullable|string',
            'body_size' => 'nullable|integer',
            'signature_text' => 'nullable|string',
            'signature_image' => 'nullable|image|max:2048',
            'bg_image' => 'nullable|image|max:4096', // Max 4MB
            'is_default' => 'nullable|boolean',
        ]);

        $certificates = $this->getCertificates();

        // Validar que no exista certificado para este curso
        if ($request->course_id) {
            foreach ($certificates as $cert) {
                if (isset($cert['course_id']) && $cert['course_id'] == $request->course_id) {
                    return back()->withInput()->withErrors(['course_id' => 'Este curso ya tiene asignado un certificado.']);
                }
            }
        }

        // Si es default, quitar default a los demás
        if ($request->has('is_default') && $request->is_default) {
            foreach ($certificates as &$cert) {
                $cert['is_default'] = false;
            }
            unset($cert); // romper referencia
        }

        $signaturePath = null;
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('public/signatures');
            $signaturePath = Storage::url($path);
        }

        $bgPath = null;
        if ($request->hasFile('bg_image')) {
            $path = $request->file('bg_image')->store('public/certificates/backgrounds');
            $bgPath = Storage::url($path);
        }

        $newCertificate = [
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'course_id' => $request->course_id ? (int) $request->course_id : null,
            'is_default' => $request->has('is_default'),
            'width' => $request->width ?? 800,
            'height' => $request->height ?? 600,
            'settings' => array_merge($request->only([
                'bg_color',
                'border_color',
                'border_width',
                'title_text',
                'title_color',
                'title_size',
                'title_font',
                'title_align',
                'title_margin',
                'body_text',
                'body_color',
                'body_size',
                'body_font',
                'secondary_text',
                'date_text',
                'student_weight',
                'student_margin',
                'course_margin',
                'date_margin',
                'signature_text',
                'signature_margin',
                'qr_position',
                'qr_size'
            ]), ['show_qr' => $request->has('show_qr')]),

            'signature_url' => $signaturePath,
            'bg_image_url' => $bgPath, // Save background URL
            'created_at' => now()->toDateTimeString(),
            'updated_at' => now()->toDateTimeString(),
        ];

        $certificates[] = $newCertificate;
        $this->saveCertificates($certificates);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificado creado correctamente.');
    }

    public function edit(Request $request, $id)
    {
        $certificates = $this->getCertificates();
        $certificate = collect($certificates)->first(function ($value) use ($id) {
            return $value['id'] == $id; // Loose comparison for string vs int
        });

        if (!$certificate) {
            return redirect()->route('admin.certificates.index')->with('error', 'Certificado no encontrado.');
        }

        // Obtener cursos disponibles + el curso actual del certificado
        $assignedCourseIds = array_filter(array_column($certificates, 'course_id'));
        // Remover el ID del curso actual de la lista de "asignados" para que aparezca en el select
        if (isset($certificate['course_id']) && $certificate['course_id']) {
            $assignedCourseIds = array_diff($assignedCourseIds, [$certificate['course_id']]);
        }

        $courses = Curso::whereNotIn('cur_id', $assignedCourseIds)
            ->orderBy('cur_nombre')
            ->get(['cur_id', 'cur_nombre']);

        // Detect mode from input or query string
        $mode = $request->input('mode') ?? $request->query('mode');
        $layout = $mode === 'modal' ? 'layouts.plain' : 'layouts.admin';

        return view('admin.certificates.form', compact('certificate', 'courses', 'layout'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'course_id' => 'nullable|integer|exists:curso,cur_id',
            'width' => 'nullable|integer|min:100',
            'height' => 'nullable|integer|min:100',
            'signature_image' => 'nullable|image|max:2048',
            'bg_image' => 'nullable|image|max:4096',
            'is_default' => 'nullable|boolean',
        ]);

        $certificates = $this->getCertificates();
        $key = -1;

        // Encontrar índice
        foreach ($certificates as $k => $c) {
            if ($c['id'] == $id) { // Loose comparison
                $key = $k;
                break;
            }
        }

        if ($key === -1) {
            return redirect()->route('admin.certificates.index')->with('error', 'Certificado no encontrado.');
        }

        // Validar unicidad curso (excluyendo el actual)
        if ($request->course_id) {
            foreach ($certificates as $k => $cert) {
                if ($k !== $key && isset($cert['course_id']) && $cert['course_id'] == $request->course_id) {
                    return back()->withInput()->withErrors(['course_id' => 'Este curso ya tiene otro certificado asignado.']);
                }
            }
        }

        // Si es default, quitar default a otros
        if ($request->has('is_default')) {
            foreach ($certificates as $k => &$cert) {
                if ($k !== $key)
                    $cert['is_default'] = false;
            }
            unset($cert);
        }

        // Actualizar firma
        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('public/signatures');
            $certificates[$key]['signature_url'] = Storage::url($path);
        }

        // Actualizar fondo
        if ($request->hasFile('bg_image')) {
            $path = $request->file('bg_image')->store('public/certificates/backgrounds');
            $certificates[$key]['bg_image_url'] = Storage::url($path);
        }

        $certificates[$key]['name'] = $request->name;
        $certificates[$key]['course_id'] = $request->course_id ? (int) $request->course_id : null;
        $certificates[$key]['is_default'] = $request->has('is_default');
        $certificates[$key]['width'] = $request->width ?? 800;
        $certificates[$key]['height'] = $request->height ?? 600;

        // Settings merging
        $settingsInput = $request->only([
            'bg_color',
            'border_color',
            'border_width',
            'title_text',
            'title_color',
            'title_size',
            'title_font',
            'title_align',
            'title_margin',
            'body_text',
            'body_color',
            'body_size',
            'body_font',
            'secondary_text',
            'date_text',
            'student_weight',
            'student_margin',
            'course_margin',
            'date_margin',
            'signature_text',
            'signature_margin',
            'qr_position',
            'qr_size'
        ]);

        // Explicitly handle checkbox (boolean)
        $settingsInput['show_qr'] = $request->has('show_qr');

        $certificates[$key]['settings'] = array_merge($certificates[$key]['settings'] ?? [], $settingsInput);
        $certificates[$key]['updated_at'] = now()->toDateTimeString();

        $this->saveCertificates($certificates);

        if ($request->get('mode') === 'modal') {
            return response()->make("<script>
                window.parent.postMessage('reload_certificates', '*');
             </script>Certificado actualizado. Cerrando...", 200);
        }

        return redirect()->route('admin.certificates.index')->with('success', 'Certificado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $certificates = $this->getCertificates();
        $filter = array_filter($certificates, function ($cert) use ($id) {
            return $cert['id'] != $id; // Loose comparison
        });

        $this->saveCertificates($filter);
        return redirect()->route('admin.certificates.index')->with('success', 'Certificado eliminado.');
    }

    // --- Métodos de Ayuda para Generación HTML/CSS (Private) ---
    private function generateHtmlCss($data)
    {
        $settings = $data['settings'] ?? [];
        $width = $data['width'] ?? 800;
        $height = $data['height'] ?? 600;
        $signatureUrl = $data['signature_url'] ?? null;
        $bgImageUrl = $data['bg_image_url'] ?? null;

        // Visual params with fallback
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
            'show_qr' => isset($settings['show_qr']) ? filter_var($settings['show_qr'], FILTER_VALIDATE_BOOLEAN) : false,
            'qr_position' => $settings['qr_position'] ?? 'left',
            'qr_size' => $settings['qr_size'] ?? 150,
        ];

        // CSS
        $bgCss = $bgImageUrl ? "background-image: url('{$bgImageUrl}'); background-size: cover; background-position: center;" : "background-color: {$params['bg_color']};";

        // QR Code Generation
        $qrHtml = "";
        if ($params['show_qr']) {
            // Generate Verification URL
            // If we are in preview, use a dummy URL or current
            $verifyUrl = route('certificates.verify', ['user' => 'Preview', 'course' => 'Preview']);
            // If data contains real user/course IDs (e.g. from Participant Controller), use them.
            // But generateHtmlCss is generic.

            // We'll use a placeholder for the URL in the generic generation, 
            // and replace it in the specific context if needed, or pass it in $data.
            if (isset($data['verification_url'])) {
                $verifyUrl = $data['verification_url'];
            }

            $qrApiUrl = "https://quickchart.io/qr?text=" . urlencode($verifyUrl) . "&size=" . $params['qr_size'];

            // Position: Fixed to bottom, Left or Right
            $qrStyle = "position: absolute; bottom: 60px; width: {$params['qr_size']}px; height: {$params['qr_size']}px;";
            // Default to left if not set
            if (($params['qr_position'] ?? 'left') === 'right') {
                $qrStyle .= " right: 60px;";
            } else {
                $qrStyle .= " left: 60px;";
            }

            $qrHtml = "<!-- QR DEBUG: show_qr=TRUE size={$params['qr_size']} pos={$params['qr_position']} url={$qrApiUrl} -->
                       <div class='cert-qr' style='{$qrStyle}; background: rgba(255,0,0,0.1); border: 2px solid red;'>
                        <img src='{$qrApiUrl}' alt='QR' style='width: 100%; height: 100%;'>
                       </div>";
        } else {
            $qrHtml = "<!-- QR DEBUG: show_qr=FALSE -->";
        }

        $css = "
            .cert-container { width: 100%; height: 100%; box-sizing: border-box; {$bgCss} border: {$params['border_width']}px solid {$params['border_color']}; display: flex; flex-direction: column; align-items: center; font-family: {$params['body_font']}; text-align: center; padding: 40px; position: relative; }
            .cert-title { color: {$params['title_color']}; font-size: {$params['title_size']}px; font-family: {$params['title_font']}; text-align: {$params['title_align']}; font-weight: bold; margin-top: {$params['title_margin']}px; margin-bottom: 20px; width: 100%; }
            .cert-body { color: {$params['body_color']}; font-size: {$params['body_size']}px; margin-bottom: 5px; }
            .cert-student { color: #000; font-weight: {$params['student_weight']}; font-size: " . ($params['body_size'] * 1.5) . "px; margin-top: {$params['student_margin']}px; margin-bottom: 5px; }
            .cert-course { color: #000; font-weight: bold; font-size: " . ($params['body_size'] * 1.25) . "px; margin-top: {$params['course_margin']}px; }
            .cert-date { margin-top: {$params['date_margin']}px; font-size: " . ($params['body_size'] * 0.85) . "px; color: #666; }
            .cert-signature { margin-top: {$params['signature_margin']}px; display: flex; flex-direction: column; align-items: center; }
            .cert-signature img { max-height: 80px; margin-bottom: 5px; }
            .cert-signature-line { width: 200px; border-top: 1px solid #333; margin-bottom: 5px; }
            .cert-signature-text { font-size: " . ($params['body_size'] * 0.8) . "px; color: #555; }
        ";

        $sigHtml = "";
        if ($signatureUrl) {
            $sigHtml .= "<img src='{$signatureUrl}' alt='Firma'>";
        }
        $sigHtml .= "<div class='cert-signature-line'></div><div class='cert-signature-text'>{$params['signature_text']}</div>";

        $html = "
            <div class='cert-container'>
                <div class='cert-title'>{$params['title_text']}</div>
                <div class='cert-body'>{$params['body_text']}</div>
                <div class='cert-student'>{nombre}</div>
                <div class='cert-body' style='margin-top: 10px;'>{$params['secondary_text']}</div>
                <div class='cert-course'>{curso}</div>
                <div class='cert-date'>{$params['date_text']} {fecha}</div>
                <div class='cert-signature'>{$sigHtml}</div>
                {$qrHtml}
            </div>
        ";

        return ['html' => $html, 'css' => $css];
    }

    public function preview(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['settings'] = $request->except(['_token', 'name', 'course_id', 'width', 'height']);
        } else {
            $id = $request->query('id');
            $certificates = $this->getCertificates();
            $data = collect($certificates)->first(function ($value) use ($id) {
                return $value['id'] == $id;
            });
            if (!$data)
                abort(404);
        }

        try {
            $generated = $this->generateHtmlCss($data);

            // Datos Mock
            $mock = [
                '{nombre}' => 'ESTUDIANTE EJEMPLO',
                '{curso}' => isset($data['course_id']) && $data['course_id'] ? strtoupper(Curso::find($data['course_id'])->cur_nombre ?? 'CURSO') : 'CURSO DEMO',
                '{fecha}' => now()->format('d/m/Y')
            ];

            $html = str_replace(array_keys($mock), array_values($mock), $generated['html']);

            return view('admin.certificates.preview', [
                'width' => $data['width'] ?? 800,
                'height' => $data['height'] ?? 600,
                'html' => $html,
                'css' => $generated['css']
            ]);
        } catch (\Exception $e) {
            return response("Error en Vista Previa: " . $e->getMessage() . " -- " . $e->getFile() . ":" . $e->getLine(), 500);
        }
    }

    public function download($login, $courseId)
    {
        $usuario = Participante::where('par_login', $login)->firstOrFail();
        $curso = Curso::findOrFail($courseId);

        // Verificar inscripción (Opcional, pero recomendado para mostrar fechas correctas)
        $inscripcion = Inscripcion::where('par_login', $login)
            ->where('cur_id', $courseId)
            ->first();

        // Buscar certificado específico o default
        $certificates = $this->getCertificates();
        $certData = collect($certificates)->firstWhere('course_id', $courseId)
            ?? collect($certificates)->firstWhere('is_default', true);

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

        // Generar URL de validación
        // FIX: Usamos explícitamente la URL de Ngrok para el QR, independientemente de dónde estemos.
        $publicBaseUrl = 'https://virulent-attractively-phebe.ngrok-free.dev';

        if ($inscripcion) {
            $hash = substr(md5($inscripcion->ins_id . 'CERTIFICATE_VALIDATION_SECRET_KEY_2024'), 0, 8);

            // Construcción manual de la ruta para asegurar dominio público
            $certData['verification_url'] = $publicBaseUrl . "/certificates/validate/" . $inscripcion->ins_id . "/" . $hash;

        } else {
            // Fallback preview
            $certData['verification_url'] = $publicBaseUrl . "/certificates/verify/" . $login . "/" . $courseId;
        }

        try {
            $generated = $this->generateHtmlCss($certData);

            $html = str_replace(
                ['{nombre}', '{curso}', '{fecha}'],
                [
                    strtoupper($usuario->par_nombre . ' ' . $usuario->par_apellido),
                    strtoupper($curso->cur_nombre),
                    // Usar fecha término del curso, o fecha actual si no tiene
                    $inscripcion && $inscripcion->cur_fecha_termino
                    ? \Carbon\Carbon::parse($inscripcion->cur_fecha_termino)->format('d/m/Y')
                    : ($curso->cur_fecha_termino
                        ? \Carbon\Carbon::parse($curso->cur_fecha_termino)->format('d/m/Y')
                        : now()->format('d/m/Y'))
                ],
                $generated['html']
            );

            return view('admin.certificates.preview', [
                'width' => $certData['width'],
                'height' => $certData['height'],
                'html' => $html,
                'css' => $generated['css']
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar el certificado: ' . $e->getMessage());
        }
    }
}
