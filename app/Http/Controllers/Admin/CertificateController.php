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
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function index(Request $request)
    {
        $allCertificates = $this->getCertificates();

        // --- 1. Filtrado (Búsqueda) ---
        if ($request->has('search') && $request->search != '') {
            $search = strtolower($request->search);
            $allCertificates = array_filter($allCertificates, function ($cert) use ($search) {
                return str_contains(strtolower($cert['name']), $search);
            });
        }

        // --- 2. Ordenamiento (Opcional, por nombre o fecha) ---
        usort($allCertificates, function ($a, $b) {
            $dateA = $a['updated_at'] ?? $a['created_at'] ?? '2000-01-01';
            $dateB = $b['updated_at'] ?? $b['created_at'] ?? '2000-01-01';
            return strtotime($dateB) - strtotime($dateA); // Descendente
        });

        // --- 3. Paginación Manual ---
        $page = $request->input('page', 1); // Página actual
        $perPage = 9; // Elementos por página
        $offset = ($page - 1) * $perPage;

        $items = array_slice($allCertificates, $offset, $perPage);
        $total = count($allCertificates);

        $certificates = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Obtener nombres de cursos para mostrar
        $courseIds = array_filter(array_column($allCertificates, 'course_id'));
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
            'bg_image' => 'nullable|image|max:4096',
            'is_default' => 'nullable|boolean',
            'page_size' => 'nullable|string|in:custom,letter,a4',
            'orientation' => 'nullable|string|in:landscape,portrait',
            'title_font' => 'nullable|string',
            'body_font' => 'nullable|string',
        ]);

        $certificates = $this->getCertificates();

        if ($request->course_id) {
            foreach ($certificates as $cert) {
                if (isset($cert['course_id']) && $cert['course_id'] == $request->course_id) {
                    return back()->withInput()->withErrors(['course_id' => 'Este curso ya tiene asignado un certificado.']);
                }
            }
        }

        if ($request->has('is_default') && $request->is_default) {
            foreach ($certificates as &$cert) {
                $cert['is_default'] = false;
            }
            unset($cert);
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

        $width = $request->width ?? 800;
        $height = $request->height ?? 600;

        $pageSize = $request->page_size ?? 'custom';
        $orientation = $request->orientation ?? 'landscape';

        if ($pageSize === 'letter') {
            if ($orientation === 'landscape') {
                $width = 1056;
                $height = 816;
            } else {
                $width = 816;
                $height = 1056;
            }
        } elseif ($pageSize === 'a4') {
            if ($orientation === 'landscape') {
                $width = 1123;
                $height = 794;
            } else {
                $width = 794;
                $height = 1123;
            }
        }

        $newCertificate = [
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'course_id' => $request->course_id ? (int) $request->course_id : null,
            'is_default' => $request->has('is_default'),
            'width' => $width,
            'height' => $height,
            'page_size' => $pageSize,
            'orientation' => $orientation,
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
            'bg_image_url' => $bgPath,
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
            return $value['id'] == $id;
        });

        if (!$certificate) {
            return redirect()->route('admin.certificates.index')->with('error', 'Certificado no encontrado.');
        }

        $assignedCourseIds = array_filter(array_column($certificates, 'course_id'));
        if (isset($certificate['course_id']) && $certificate['course_id']) {
            $assignedCourseIds = array_diff($assignedCourseIds, [$certificate['course_id']]);
        }

        $courses = Curso::whereNotIn('cur_id', $assignedCourseIds)
            ->orderBy('cur_nombre')
            ->get(['cur_id', 'cur_nombre']);

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
            'page_size' => 'nullable|string|in:custom,letter,a4',
            'orientation' => 'nullable|string|in:landscape,portrait',
        ]);

        $certificates = $this->getCertificates();
        $key = -1;

        foreach ($certificates as $k => $c) {
            if ($c['id'] == $id) {
                $key = $k;
                break;
            }
        }

        if ($key === -1) {
            return redirect()->route('admin.certificates.index')->with('error', 'Certificado no encontrado.');
        }

        if ($request->course_id) {
            foreach ($certificates as $k => $cert) {
                if ($k !== $key && isset($cert['course_id']) && $cert['course_id'] == $request->course_id) {
                    return back()->withInput()->withErrors(['course_id' => 'Este curso ya tiene otro certificado asignado.']);
                }
            }
        }

        if ($request->has('is_default')) {
            foreach ($certificates as $k => &$cert) {
                if ($k !== $key)
                    $cert['is_default'] = false;
            }
            unset($cert);
        }

        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('public/signatures');
            $certificates[$key]['signature_url'] = Storage::url($path);
        }

        if ($request->hasFile('bg_image')) {
            $path = $request->file('bg_image')->store('public/certificates/backgrounds');
            $certificates[$key]['bg_image_url'] = Storage::url($path);
        }

        $pageSize = $request->page_size ?? $certificates[$key]['page_size'] ?? 'custom';
        $orientation = $request->orientation ?? $certificates[$key]['orientation'] ?? 'landscape';
        $width = $request->width ?? $certificates[$key]['width'];
        $height = $request->height ?? $certificates[$key]['height'];

        if ($pageSize === 'letter') {
            if ($orientation === 'landscape') {
                $width = 1056;
                $height = 816;
            } else {
                $width = 816;
                $height = 1056;
            }
        } elseif ($pageSize === 'a4') {
            if ($orientation === 'landscape') {
                $width = 1123;
                $height = 794;
            } else {
                $width = 794;
                $height = 1123;
            }
        }

        $certificates[$key]['name'] = $request->name;
        $certificates[$key]['course_id'] = $request->course_id ? (int) $request->course_id : null;
        $certificates[$key]['is_default'] = $request->has('is_default');
        $certificates[$key]['width'] = $width;
        $certificates[$key]['height'] = $height;
        $certificates[$key]['page_size'] = $pageSize;
        $certificates[$key]['orientation'] = $orientation;

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
        $settingsInput['show_qr'] = $request->has('show_qr');

        $certificates[$key]['settings'] = array_merge($certificates[$key]['settings'] ?? [], $settingsInput);
        $certificates[$key]['updated_at'] = now()->toDateTimeString();

        $this->saveCertificates($certificates);

        if ($request->get('mode') === 'modal') {
            return response()->make("<script>window.parent.postMessage('reload_certificates', '*');</script>Certificado actualizado. Cerrando...", 200);
        }

        return redirect()->route('admin.certificates.index')->with('success', 'Certificado actualizado correctamente.');
    }

    public function destroy($id)
    {
        $certificates = $this->getCertificates();
        $filter = array_filter($certificates, function ($cert) use ($id) {
            return $cert['id'] != $id;
        });
        $this->saveCertificates($filter);
        return redirect()->route('admin.certificates.index')->with('success', 'Certificado eliminado.');
    }

    private function generateHtmlCss($data)
    {
        $settings = $data['settings'] ?? [];
        $width = $data['width'] ?? 800;
        $height = $data['height'] ?? 600;
        $signatureUrl = $data['signature_url'] ?? null;
        $bgImageUrl = $data['bg_image_url'] ?? null;

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

        $bgCss = $bgImageUrl ? "background-image: url('{$bgImageUrl}'); background-size: cover; background-position: center;" : "background-color: {$params['bg_color']};";

        $qrHtml = "";
        if ($params['show_qr']) {
            $verifyUrl = route('certificates.verify', ['user' => 'Preview', 'course' => 'Preview']);
            if (isset($data['verification_url'])) {
                $verifyUrl = $data['verification_url'];
            }
            $qrApiUrl = "https://quickchart.io/qr?text=" . urlencode($verifyUrl) . "&size=" . $params['qr_size'];
            $qrStyle = "position: absolute; bottom: 60px; width: {$params['qr_size']}px; height: {$params['qr_size']}px;";
            if (($params['qr_position'] ?? 'left') === 'right') {
                $qrStyle .= " right: 60px;";
            } else {
                $qrStyle .= " left: 60px;";
            }
            $qrHtml = "<div class='cert-qr' style='{$qrStyle};'><img src='{$qrApiUrl}' alt='QR' style='width: 100%; height: 100%;'></div>";
        }

        $css = "
            .cert-container { width: 100%; height: 100%; box-sizing: border-box; {$bgCss} border: {$params['border_width']}px solid {$params['border_color']}; display: flex; flex-direction: column; align-items: center; font-family: {$params['body_font']}; text-align: center; padding: 40px; position: relative; }
            .cert-title { color: {$params['title_color']}; font-size: {$params['title_size']}px; font-family: {$params['title_font']}; text-align: {$params['title_align']}; font-weight: bold; margin-top: {$params['title_margin']}px; margin-bottom: 20px; width: 100%; }
            .cert-body { color: {$params['body_color']}; font-size: {$params['body_size']}px; margin-bottom: 5px; font-family: {$params['body_font']}; }
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

            // Handle File Previews (Base64)
            if ($request->hasFile('bg_image')) {
                $file = $request->file('bg_image');
                $type = $file->getMimeType();
                $content = base64_encode(file_get_contents($file->getRealPath()));
                $data['bg_image_url'] = "data:$type;base64,$content";
            } else {
                if (isset($request->id)) {
                    $all = $this->getCertificates();
                    $existing = collect($all)->firstWhere('id', $request->id);
                    if ($existing) {
                        $data['bg_image_url'] = $existing['bg_image_url'] ?? null;
                    }
                }
            }

            if ($request->hasFile('signature_image')) {
                $file = $request->file('signature_image');
                $type = $file->getMimeType();
                $content = base64_encode(file_get_contents($file->getRealPath()));
                $data['signature_url'] = "data:$type;base64,$content";
            } else {
                if (isset($request->id)) {
                    $all = $this->getCertificates();
                    $existing = collect($all)->firstWhere('id', $request->id);
                    if ($existing) {
                        $data['signature_url'] = $existing['signature_url'] ?? null;
                    }
                }
            }

            $pageSize = $request->page_size ?? 'custom';
            $orientation = $request->orientation ?? 'landscape';

            if ($pageSize === 'letter') {
                if ($orientation === 'landscape') {
                    $data['width'] = 1056;
                    $data['height'] = 816;
                } else {
                    $data['width'] = 816;
                    $data['height'] = 1056;
                }
            } elseif ($pageSize === 'a4') {
                if ($orientation === 'landscape') {
                    $data['width'] = 1123;
                    $data['height'] = 794;
                } else {
                    $data['width'] = 794;
                    $data['height'] = 1123;
                }
            }

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

        $inscripcion = Inscripcion::where('par_login', $login)
            ->where('cur_id', $courseId)
            ->first();

        $certificates = $this->getCertificates();
        $certData = collect($certificates)->firstWhere('course_id', $courseId)
            ?? collect($certificates)->firstWhere('is_default', true);

        if (!$certData) {
            $certData = [
                'width' => 800,
                'height' => 600,
                'name' => 'Certificado Por Defecto',
                'settings' => [
                    'bg_color' => '#ffffff',
                    'border_color' => '#1e40af',
                    'border_width' => 10,
                    'title_text' => 'CERTIFICADO DE APROBACIÓN',
                    'title_color' => '#111827',
                    'title_size' => 45,
                    'title_font' => "'Helvetica', sans-serif",
                    'title_margin' => 30,
                    'body_text' => 'Se otorga el presente reconocimiento a:',
                    'body_color' => '#4b5563',
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

        $publicBaseUrl = 'https://virulent-attractively-phebe.ngrok-free.dev';

        if ($inscripcion) {
            $hash = substr(md5($inscripcion->ins_id . 'CERTIFICATE_VALIDATION_SECRET_KEY_2024'), 0, 8);
            $certData['verification_url'] = $publicBaseUrl . "/certificates/validate/" . $inscripcion->ins_id . "/" . $hash;

        } else {
            $certData['verification_url'] = $publicBaseUrl . "/certificates/verify/" . $login . "/" . $courseId;
        }

        try {
            $generated = $this->generateHtmlCss($certData);

            $html = str_replace(
                ['{nombre}', '{curso}', '{fecha}'],
                [
                    strtoupper($usuario->par_nombre . ' ' . $usuario->par_apellido),
                    strtoupper($curso->cur_nombre),
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
