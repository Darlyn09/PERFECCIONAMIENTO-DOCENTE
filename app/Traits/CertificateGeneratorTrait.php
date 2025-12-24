<?php

namespace App\Traits;

trait CertificateGeneratorTrait
{
    /**
     * Replaces placeholders in the HTML with data values.
     */
    protected function applyReplacements($html, $data)
    {
        $search = array_keys($data);
        $replace = array_values($data);
        return str_replace($search, $replace, $html);
    }

    /**
     * Generates the HTML and CSS for a certificate based on data/config.
     * 
     * @param mixed $data Certificate model or Array
     * @param bool $isPreview Whether generation is for a preview
     * @return array ['html' => string, 'css' => string]
     */
    protected function generateHtmlCss($data, $isPreview = false)
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
            $verifyUrl = "https://www.cfrd.cl/validacion"; // Default fallback
            if (is_array($data) && isset($data['verification_url'])) {
                $verifyUrl = $data['verification_url'];
            }
            $qrApiUrl = "https://quickchart.io/qr?text=" . urlencode($verifyUrl) . "&size=" . $params['qr_size'];
            // Quickchart returns an image directly.

            // Positioning
            $qrStyle = "position: absolute; bottom: 50px;";
            if ($params['qr_position'] === 'left') {
                $qrStyle .= " left: 50px;";
            } else {
                $qrStyle .= " right: 50px;";
            }
            $qrStyle .= " width: {$params['qr_size']}px; height: {$params['qr_size']}px;";

            $qrHtml = "<img src='{$qrApiUrl}' style='{$qrStyle}' alt='QR Is Missing' />";
        }

        $sigHtml = "";
        if ($signatureUrl) {
            $sigHtml = "<img src='{$signatureUrl}' style='max-height: 80px; display: block; margin: 0 auto;' />";
        }
        $sigHtml .= "<div style='margin-top: 10px; font-weight: bold;'>{$params['signature_text']}</div>";


        $css = "
            @page {
                size: {$width}px {$height}px;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                font-family: 'Arial', sans-serif;
                width: {$width}px;
                height: {$height}px;
            }
            .cert-container {
                width: 100%;
                height: 100%;
                box-sizing: border-box;
                position: relative;
                overflow: hidden;
                border: {$params['border_width']}px solid {$params['border_color']};
                padding: 40px;
                text-align: center;
                {$bgCss}
            }
            /* Legacy Classes Support */
            .cert-title {
                color: {$params['title_color']};
                font-size: {$params['title_size']}px;
                font-family: {$params['title_font']};
                text-align: {$params['title_align']};
                margin-top: {$params['title_margin']}px;
                margin-bottom: 20px;
                font-weight: bold;
            }
            .cert-body {
                color: {$params['body_color']};
                font-size: {$params['body_size']}px;
                font-family: {$params['body_font']};
                margin-bottom: 10px;
            }
            .cert-student {
                font-size: " . ($params['body_size'] * 1.5) . "px;
                font-weight: {$params['student_weight']};
                margin: {$params['student_margin']}px 0;
                color: #000;
            }
            .cert-course {
                font-size: " . ($params['body_size'] * 1.2) . "px;
                font-weight: bold;
                margin: {$params['course_margin']}px 0;
                color: #000;
            }
            .cert-date {
                font-size: {$params['body_size']}px;
                margin-top: {$params['date_margin']}px;
            }
            .cert-signature {
                margin-top: {$params['signature_margin']}px;
            }
        ";

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
}
