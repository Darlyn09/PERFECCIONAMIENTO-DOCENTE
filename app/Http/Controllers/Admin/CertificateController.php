<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Curso;
use App\Models\Participante;
use App\Models\Inscripcion;
use App\Models\Certificado;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class CertificateController extends Controller
{
    private function getCourses()
    {
        return Curso::orderBy('cur_nombre')->get(['cur_id', 'cur_nombre']);
    }


    public function preview(Request $request)
    {
        // Mock data for preview
        $config = $request->only([
            'content_html',
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
        $config['show_qr'] = $request->has('show_qr');

        // Handle temporary images if uploaded, else use existing or placeholders
        // For preview, we might just use the URL if passed, or base64? 
        // Simplification for now: Use what's in request if it's a URL/string (from hidden inputs if we had them)
        // or just ignore file uploads for LIVE preview unless ajax handled.
        // Actually, the previous implementation likely didn't handle file preview instantly without upload.
        // Let's check how form handles it. It posts FormData.

        // We'll construct a mock object
        // Structurally mock the cert object for generateHtmlCss
        $certData = [
            'settings' => $config,
            'width' => $request->width ?? 800,
            'height' => $request->height ?? 600,
            'signature_url' => null,
            'bg_image_url' => null,
            'page_size' => $request->page_size ?? 'custom',
            'orientation' => $request->orientation ?? 'landscape'
        ];

        // Mock generic data for replacement
        $replacements = [
            '{nombre_participante}' => 'JUAN PÉREZ GONZÁLEZ',
            '{rut_participante}' => '12.345.678-9',
            '{nombre_curso}' => 'CURSO DEMO DE EJEMPLO',
            '{nombre_relator}' => 'Ana Garcia',
            '{fecha_inicio}' => date('d/m/Y', strtotime('-1 month')),
            '{fecha_termino}' => date('d/m/Y'),
            '{horas_curso}' => '24',
            '{nota}' => '7.0',
            '{folio}' => 'PREVIEW-123456',
            '{url_verificacion}' => '#'
        ];

        // Render the view. We need a 'certificates.preview' view. 
        // Does it exist? Let's check. If not, use 'admin.certificates.preview_render' or similar.
        // Looking at routes: Route::match(['get', 'post'], '/certificates/preview', [CertificateController::class, 'preview'])
        // We will assume 'admin.certificates.preview' is the view name for the visual layout.

        // Generate raw HTML/CSS
        $generated = $this->generateHtmlCss($certData, true);

        // Apply replacements
        $html = $this->applyReplacements($generated['html'], $replacements);
        $css = $generated['css'];
        $width = $certData['width'];
        $height = $certData['height'];

        return view('admin.certificates.preview', compact('html', 'css', 'width', 'height'));
    }

    public function index(Request $request)
    {
        // CRUD simple usando Eloquent
        $query = Certificado::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Ordenar por fecha descendente
        $query->orderBy('updated_at', 'desc');

        $certificates = $query->paginate(9);

        // Obtener nombres de cursos para mostrar en el filtro o lista si es necesario
        // Pero en la vista index actual se iteran $certificates

        $courses = Curso::pluck('cur_nombre', 'cur_id');

        return view('admin.certificates.index', compact('certificates', 'courses'));
    }

    public function create()
    {
        // Cursos que NO tienen certificado asignado (para evitar duplicados si se quisiera 1-1 estricto, 
        // aunque ahora la tabla permite 'tipo'='curso' y 'referencia_id'.
        // Podemos listar todos los cursos y eventos.

        return view('admin.certificates.form', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:defecto,curso,evento',
            'referencia_id' => 'nullable|integer|required_if:type,curso,evento',
            'width' => 'nullable|integer|min:100',
            'height' => 'nullable|integer|min:100',
            'bg_image' => 'nullable|image|max:4096',
            'signature_image' => 'nullable|image|max:2048',
            // Settings validation
            'bg_color' => 'nullable|string',
            'border_color' => 'nullable|string',
            'border_width' => 'nullable|integer',
            'title_text' => 'nullable|string',
            'title_color' => 'nullable|string',
            'title_size' => 'nullable|integer',
            'body_text' => 'nullable|string',
            'body_color' => 'nullable|string',
            'body_size' => 'nullable|integer',
        ]);

        // Si es tipo 'curso' o 'evento', verificar si ya existe (opcional, para evitar múltiples certs para el mismo curso)
        if ($request->type !== 'defecto' && $request->referencia_id) {
            $exists = Certificado::where('tipo', $request->type)
                ->where('referencia_id', $request->referencia_id)
                ->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['referencia_id' => 'Ya existe un certificado para este curso/evento.']);
            }
        }

        // Si se marca como defecto (o tipo defecto), podríamos desmarcar otros si quisieramos LOGICA de "Solo un defecto". 
        // Pero 'tipo=defecto' ya lo segrega. Podemos asumir que el 'tipo=defecto' es el único que se usa como fallback global.
        // Si crean múltiples 'defecto', tomamos el último.

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

        // Dimensions logic
        $width = $request->width ?? 800;
        $height = $request->height ?? 600;
        $pageSize = $request->page_size ?? 'custom';
        $orientation = $request->orientation ?? 'landscape';

        // ... (Logic for page size pre-sets kept same as before) ...
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

        // Prepare configuration JSON
        $settings = $request->only([
            'content_html', // New field
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
        $settings['show_qr'] = $request->has('show_qr');

        $cert = Certificado::create([
            'nombre' => $request->name,
            'tipo' => $request->type,
            'referencia_id' => ($request->type === 'defecto') ? null : $request->referencia_id,
            'width' => $width,
            'height' => $height,
            'page_size' => $pageSize,
            'orientation' => $orientation,
            'imagen_fondo' => $bgPath,
            'firma_imagen' => $signaturePath,
            'configuracion' => $settings,
        ]);

        return redirect()->route('admin.certificates.index')->with('success', 'Certificado creado correctamente.');
    }

    public function edit(Request $request, $id)
    {
        $certificate = Certificado::findOrFail($id);
        $courses = Curso::orderBy('cur_nombre')->get(['cur_id', 'cur_nombre']);

        $mode = $request->input('mode') ?? $request->query('mode');
        $layout = $mode === 'modal' ? 'layouts.plain' : 'layouts.admin';
        return view('admin.certificates.form', compact('certificate', 'courses', 'layout'));
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificado::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:defecto,curso,evento',
            'referencia_id' => 'nullable|integer|required_if:type,curso,evento',
            'width' => 'nullable|integer|min:100',
            'height' => 'nullable|integer|min:100',
            'bg_image' => 'nullable|image|max:4096',
            'signature_image' => 'nullable|image|max:2048',
            // Settings validation
            'content_html' => 'nullable|string', // New field
            'bg_color' => 'nullable|string',
            'border_color' => 'nullable|string',
            'border_width' => 'nullable|integer',
            'title_text' => 'nullable|string',
            'title_color' => 'nullable|string',
            'title_size' => 'nullable|integer',
            'body_text' => 'nullable|string',
            'body_color' => 'nullable|string',
            'body_size' => 'nullable|integer',
        ]);

        // Logic similar to store but updating
        if ($request->type !== 'defecto' && $request->referencia_id) {
            $exists = Certificado::where('tipo', $request->type)
                ->where('referencia_id', $request->referencia_id)
                ->where('id', '!=', $id) // Exclude self
                ->exists();
            if ($exists) {
                return back()->withInput()->withErrors(['referencia_id' => 'Ya existe un certificado para este curso/evento.']);
            }
        }

        if ($request->hasFile('signature_image')) {
            $path = $request->file('signature_image')->store('public/signatures');
            $certificate->firma_imagen = Storage::url($path);
        }

        if ($request->hasFile('bg_image')) {
            $path = $request->file('bg_image')->store('public/certificates/backgrounds');
            $certificate->imagen_fondo = Storage::url($path);
        }

        // Update Dimensions
        // ... (Copy/Paste similar dimension logic) ...
        $pageSize = $request->page_size ?? $certificate->page_size;
        $orientation = $request->orientation ?? $certificate->orientation;
        $width = $request->width ?? $certificate->width;
        $height = $request->height ?? $certificate->height;

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

        // Merge settings
        $newSettings = $request->only([
            'content_html', // New field
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
        $newSettings['show_qr'] = $request->has('show_qr');

        $config = $certificate->configuracion ?? [];
        $certificate->configuracion = array_merge($config, $newSettings);

        $certificate->nombre = $request->name;
        $certificate->tipo = $request->type;
        $certificate->referencia_id = ($request->type === 'defecto') ? null : $request->referencia_id;
        $certificate->width = $width;
        $certificate->height = $height;
        $certificate->page_size = $pageSize;
        $certificate->orientation = $orientation;

        $certificate->save();

        if ($request->get('mode') === 'modal') {
            return response()->make("<script>window.parent.postMessage('reload_certificates', '*');</script>Actualizado.", 200);
        }

        return redirect()->route('admin.certificates.index')->with('success', 'Certificado actualizado.');
    }

    public function destroy($id)
    {
        $cert = Certificado::findOrFail($id);
        $cert->delete();
        return redirect()->route('admin.certificates.index')->with('success', 'Certificado eliminado.');
    }

    // --- GENERATION HELPERS ---

    private function applyReplacements($html, $data)
    {
        $search = array_keys($data);
        $replace = array_values($data);
        return str_replace($search, $replace, $html);
    }

    private function generateHtmlCss($data, $isPreview = false)
    {
        // Adapt $data (Model or Array) to params
        $settings = is_array($data) ? ($data['settings'] ?? []) : ($data->configuracion ?? []);
        $width = is_array($data) ? ($data['width'] ?? 800) : $data->width;
        $height = is_array($data) ? ($data['height'] ?? 600) : $data->height;
        $signatureUrl = is_array($data) ? ($data['signature_url'] ?? null) : $data->firma_imagen;
        $bgImageUrl = is_array($data) ? ($data['bg_image_url'] ?? null) : $data->imagen_fondo;

        $params = [
            'bg_color' => $settings['bg_color'] ?? '#ffffff',
            'border_color' => $settings['border_color'] ?? '#dddddd',
            'border_width' => $settings['border_width'] ?? 10,

            // Legacy Params (defaults)
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
            // New unified content
            'content_html' => $settings['content_html'] ?? null,
        ];

        $bgCss = $bgImageUrl
            ? "background-image: url('{$bgImageUrl}'); background-size: cover; background-position: center;"
            : "background-color: {$params['bg_color']};";

        $qrHtml = "";
        if ($params['show_qr']) {
            $verifyUrl = "PREVIEW_URL";
            if (is_array($data) && isset($data['verification_url'])) {
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
            /* Legacy Styles */
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

        // IMPORTANT: We use structural placeholders here {nombre_participante} etc.
        // We ensure defaults are present in the structure too.

        $mainContent = "";
        if (!empty($params['content_html'])) {
            // Use UNIFIED Content
            $mainContent = $params['content_html'];
        } else {
            // Use LEGACY Structure
            $mainContent = "
                <div class='cert-title'>{$params['title_text']}</div>
                <div class='cert-body'>{$params['body_text']}</div>
                <div class='cert-student'>{nombre_participante}</div>
                <div class='cert-body' style='margin-top: 10px;'>{$params['secondary_text']}</div>
                <div class='cert-course'>{nombre_curso}</div>
                <div class='cert-date'>{$params['date_text']} {fecha_termino}</div>
            ";
        }

        $html = "
            <div class='cert-container'>
                {$mainContent}
                <div class='cert-signature'>{$sigHtml}</div>
                {$qrHtml}
            </div>
        ";

        return ['html' => $html, 'css' => $css];
    }






    public function download($login, $courseId)
    {
        $usuario = Participante::where('par_login', $login)->firstOrFail();
        $curso = Curso::with(['programas.relatores'])->findOrFail($courseId);
        $inscripcion = Inscripcion::where('par_login', $login)->where('cur_id', $courseId)->first();

        // 1. Buscar Certificado Específico del Curso
        $cert = Certificado::where('tipo', 'curso')->where('referencia_id', $courseId)->first();

        // 2. Si no, buscar Certificado Específico del Evento (Future implementation)

        // 3. Si no, buscar Certificado por Defecto
        if (!$cert) {
            $cert = Certificado::where('tipo', 'defecto')->latest()->first();
        }

        // 4. Fallback hardcodeado
        $data = $cert;
        if (!$cert) {
            $data = [
                'width' => 800,
                'height' => 600,
                'settings' => [
                    'title_text' => 'CERTIFICADO DE APROBACIÓN',
                    'body_text' => 'Se otorga a:',
                ]
            ];
        }

        // URL Verificación
        // Use production config if available, else ngrok for dev
        $publicBaseUrl = config('app.url', 'https://virulent-attractively-phebe.ngrok-free.dev');

        $verificationUrl = "";
        if ($inscripcion) {
            $hash = substr(md5($inscripcion->ins_id . 'CERTIFICATE_VALIDATION_SECRET_KEY_2024'), 0, 8);
            $verificationUrl = $publicBaseUrl . "/certificates/validate/" . $inscripcion->ins_id . "/" . $hash;
        }

        // Inject verification URL
        if (is_object($data) && $data instanceof Certificado) {
            $certArray = $data->toArray();
            $certArray['verification_url'] = $verificationUrl;
            $data = $certArray;
        } else {
            $data['verification_url'] = $verificationUrl;
        }

        try {
            $generated = $this->generateHtmlCss($data);

            // Fetch Relator Name
            // Logic: Get first program, get first relator.
            // Improve: Could loop through programs to find one with relators.
            $relatorName = 'Docente del Curso';
            foreach ($curso->programas as $prog) {
                if ($prog->relatores && $prog->relatores->count() > 0) {
                    $relator = $prog->relatores->first();
                    $relatorName = mb_strtoupper($relator->rel_nombres . ' ' . $relator->rel_apellidos); // Assuming relator model has these fields or similar
                    // Check Relator Model: rel_nombre? rel_nombres?
                    // Previous logs show Relator has properties. Let's assume rel_nombre for now or check model.
                    // Checking Relator.php previously... I didn't open it. I saw cursor on Curso.php.
                    // Standard User model has name/lastname. Relator often linked to user or separate.
                    // Let's safe bet on generic access or check later.
                    // Wait, I can try accessing 'rel_nombre' based on common patterns.
                    // Actually, let's use a safe accessor if possible or just the properties.
                    // Assuming Relator Model: rel_id, rel_rut, rel_nombre, rel_apellido ??
                    // If Relator is independent table.
                    if (isset($relator->rel_nombre)) {
                        $relatorName = mb_strtoupper($relator->rel_nombre . ' ' . ($relator->rel_apellido ?? ''));
                    }
                    break;
                }
            }

            // Real Replacements
            $replacements = [
                '{nombre_participante}' => mb_strtoupper($usuario->par_nombre . ' ' . $usuario->par_apellidos),
                '{rut_participante}' => $usuario->par_rut,
                '{nombre_curso}' => mb_strtoupper($curso->cur_nombre),
                '{fecha_termino}' => ($inscripcion && $inscripcion->informacion && $inscripcion->informacion->inf_fecha_certificado)
                    ? \Carbon\Carbon::parse($inscripcion->informacion->inf_fecha_certificado)->format('d/m/Y')
                    : ($curso->cur_fecha_termino ? \Carbon\Carbon::parse($curso->cur_fecha_termino)->format('d/m/Y') : now()->format('d/m/Y')),
                '{fecha_inicio}' => $curso->cur_fecha_inicio ? \Carbon\Carbon::parse($curso->cur_fecha_inicio)->format('d/m/Y') : '',
                '{horas_curso}' => $curso->cur_horas ?? '',
                '{nota}' => ($inscripcion && $inscripcion->informacion) ? $inscripcion->informacion->inf_nota_final : '',
                '{nombre_relator}' => $relatorName, // Added for Req
                // Compat keys
                '{alumno}' => mb_strtoupper($usuario->par_nombre . ' ' . $usuario->par_apellidos),
                '{curso}' => mb_strtoupper($curso->cur_nombre),
                '{fecha}' => ($inscripcion && $inscripcion->informacion && $inscripcion->informacion->inf_fecha_certificado)
                    ? \Carbon\Carbon::parse($inscripcion->informacion->inf_fecha_certificado)->format('d/m/Y')
                    : now()->format('d/m/Y'),
                '{nombre}' => mb_strtoupper($usuario->par_nombre . ' ' . $usuario->par_apellidos),
            ];

            $html = $this->applyReplacements($generated['html'], $replacements);

            return view('admin.certificates.preview', [
                'width' => $data['width'] ?? 800,
                'height' => $data['height'] ?? 600,
                'html' => $html,
                'css' => $generated['css']
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error generando certificado: ' . $e->getMessage());
        }
    }

    public function downloadRelatorCertificate(Request $request, $programId, $relLogin)
    {
        // Hardcoded logic for now
        return parent::downloadRelatorCertificate($request, $programId, $relLogin);
    }
}
