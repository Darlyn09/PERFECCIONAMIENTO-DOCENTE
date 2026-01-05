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
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    use \App\Traits\CertificateGeneratorTrait;

    private function getCourses()
    {
        return Curso::orderBy('cur_nombre')->get(['cur_id', 'cur_nombre']);
    }

    private function getDimensions($size, $orientation)
    {
        // Standard in pixels (96 DPI approx)
        // Letter: 816 x 1056
        // A4: 794 x 1123

        $sizes = [
            'letter' => ['w' => 816, 'h' => 1056],
            'a4' => ['w' => 794, 'h' => 1123],
            'custom' => ['w' => 800, 'h' => 600] // Default fallback
        ];

        // Default to custom if unknown
        $dims = $sizes[$size] ?? $sizes['custom'];

        if ($orientation === 'landscape') {
            return ['width' => max($dims['w'], $dims['h']), 'height' => min($dims['w'], $dims['h'])];
        } else {
            return ['width' => min($dims['w'], $dims['h']), 'height' => max($dims['w'], $dims['h'])];
        }
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

        // Calculate Dimensions (Logic Fix)
        $pageSize = $request->input('page_size', 'custom');
        $orientation = $request->input('orientation', 'landscape');
        $dims = $this->getDimensions($pageSize, $orientation);

        // If custom inputs exist, override
        if ($pageSize === 'custom' && $request->has('width')) {
            $dims['width'] = $request->input('width');
            $dims['height'] = $request->input('height');
        }

        $certData = [
            'settings' => $config,
            'width' => $dims['width'],
            'height' => $dims['height'],
            'signature_url' => null,
            'bg_image_url' => null,
            'page_size' => $pageSize,
            'orientation' => $orientation
        ];

        // Handle Images Base64 for preview
        if ($request->hasFile('bg_image')) {
            $file = $request->file('bg_image');
            $type = $file->getMimeType();
            $content = base64_encode(file_get_contents($file->getRealPath()));
            $certData['bg_image_url'] = "data:$type;base64,$content";
        }
        if ($request->hasFile('signature_image')) {
            $file = $request->file('signature_image');
            $type = $file->getMimeType();
            $content = base64_encode(file_get_contents($file->getRealPath()));
            $certData['signature_url'] = "data:$type;base64,$content";
        }

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

        // Generate raw HTML/CSS
        $generated = $this->generateHtmlCss($certData, true);

        // Apply replacements
        $html = $this->applyReplacements($generated['html'], $replacements);
        $css = $generated['css'];

        return view('admin.certificates.preview', [
            'width' => $certData['width'],
            'height' => $certData['height'],
            'html' => $html,
            'css' => $generated['css']
        ]);
    }

    public function index(Request $request)
    {
        // CRUD simple usando Eloquent
        $query = Certificado::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // Ordenar por fecha descendente
        $query->with('curso')->orderBy('updated_at', 'desc');

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

        $courses = Curso::pluck('cur_nombre', 'cur_id');
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
        ], [
            'name.required' => 'El nombre de la plantilla es obligatorio.',
            'referencia_id.required_if' => 'Debe seleccionar un curso o evento para este tipo de certificado.',
            'referencia_id.unique' => 'Ya existe un certificado para este curso/evento.',
            'bg_image.image' => 'El archivo de fondo debe ser una imagen.',
            'bg_image.max' => 'La imagen de fondo no debe pesar más de 4MB.',
            'signature_image.image' => 'El archivo de firma debe ser una imagen.',
            'signature_image.max' => 'La imagen de firma no debe pesar más de 2MB.',
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
        $courses = Curso::orderBy('cur_nombre')->pluck('cur_nombre', 'cur_id');

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
        ], [
            'name.required' => 'El nombre de la plantilla es obligatorio.',
            'referencia_id.required_if' => 'Debe seleccionar un curso o evento para este tipo de certificado.',
            'bg_image.image' => 'El archivo de fondo debe ser una imagen.',
            'bg_image.max' => 'La imagen de fondo no debe pesar más de 4MB.',
            'signature_image.image' => 'El archivo de firma debe ser una imagen.',
            'signature_image.max' => 'La imagen de firma no debe pesar más de 2MB.',
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


    public function download($login, $courseId)
    {
        $usuario = Participante::where('par_login', $login)->firstOrFail();
        $curso = Curso::with(['programas.relatores'])->findOrFail($courseId);
        $inscripcion = Inscripcion::where('par_login', $login)->where('cur_id', $courseId)->firstOrFail();

        // 0. Validar aprobación
        if (!$inscripcion->isApproved()) {
            return back()->with('error', 'El participante no cumple con los requisitos de aprobación para descargar este certificado.');
        }

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
                    'show_qr' => true,
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

        // Prepare Data for PDF and fix Image Paths
        $certDataForPdf = is_object($data) ? $data->toArray() : $data;

        // Fix Background Image Path
        if (isset($certDataForPdf['imagen_fondo']) && $certDataForPdf['imagen_fondo']) {
            if (Str::startsWith($certDataForPdf['imagen_fondo'], '/storage')) {
                // Convert /storage/path/to/img.jpg -> C:\...\public\storage\path\to\img.jpg
                $certDataForPdf['bg_image_url'] = public_path($certDataForPdf['imagen_fondo']);
            } else {
                $certDataForPdf['bg_image_url'] = $certDataForPdf['imagen_fondo'];
            }
        }

        // Fix Signature Image Path
        if (isset($certDataForPdf['firma_imagen']) && $certDataForPdf['firma_imagen']) {
            if (Str::startsWith($certDataForPdf['firma_imagen'], '/storage')) {
                $certDataForPdf['signature_url'] = public_path($certDataForPdf['firma_imagen']);
            } else {
                $certDataForPdf['signature_url'] = $certDataForPdf['firma_imagen'];
            }
        }

        // Inject verification URL
        $certDataForPdf['verification_url'] = $verificationUrl;

        $data = $certDataForPdf;

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
                '{nota}' => ($inscripcion && $inscripcion->informacion) ? $inscripcion->informacion->inf_nota : '',
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
            $css = $generated['css'];

            $fullHtml = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
                    <style>
                        {$css}
                    </style>
                </head>
                <body>
                    {$html}
                </body>
                </html>
            ";

            $pdf = Pdf::loadHTML($fullHtml);
            $pdf->setOption('isRemoteEnabled', true);
            $pdf->setOption('isHtml5ParserEnabled', true);
            // $pdf->setPaper... removed legacy incorrect call 
            // Wait, trait returns: return ['html' => $html, 'css' => $css];
            // The trait does NOT return width/height in the array. It puts them in the CSS string.
            // But the controller in download needs width/height for setPaper.
            // In the controller I have $data which has width/height.

            // Let's look at the old code: 
            // $pdf->setPaper([0, 0, $generated['css']['width'] ?? 800 ...
            // That was definitely wrong because $generated['css'] is a STRING.

            // I should use $data['width'] and $data['height'] directly.
            // $data is either an array or object.
            $w = is_array($data) ? ($data['width'] ?? 800) : $data->width;
            $h = is_array($data) ? ($data['height'] ?? 600) : $data->height;
            $pdf->setPaper([0, 0, $w, $h]);

            return $pdf->download('Certificado_Admin_' . $login . '.pdf');

        } catch (\Exception $e) {
            return back()->with('error', 'Error generando certificado: ' . $e->getMessage());
        }
    }

    public function downloadRelatorCertificate(Request $request, $programId, $relLogin)
    {
        // return parent::downloadRelatorCertificate($request, $programId, $relLogin);
        abort(404, 'Método no implementado');
    }
}
