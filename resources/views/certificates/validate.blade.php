<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Certificado - System</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .validation-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
            border-top: 6px solid #10b981;
            /* Green for success default */
        }

        .validation-card.invalid {
            border-top-color: #ef4444;
            /* Red for invalid */
        }

        .icon-container {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .success-icon {
            color: #10b981;
        }

        .error-icon {
            color: #ef4444;
        }

        h1 {
            color: #1f2937;
            margin-bottom: 10px;
            font-size: 24px;
        }

        h2 {
            color: #4b5563;
            font-size: 18px;
            margin-top: 5px;
            font-weight: normal;
        }

        .detail-row {
            margin-top: 30px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }

        .detail-label {
            display: block;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 600;
            color: #111827;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #9ca3af;
        }

        .verification-code {
            background: #f9fafb;
            padding: 8px;
            border-radius: 6px;
            font-family: monospace;
            color: #6b7280;
            font-size: 14px;
            margin-top: 20px;
            display: inline-block;
        }
    </style>
</head>

<body>

    @if($isValid)
        <div class="validation-card">
            <div class="icon-container success-icon">✓</div>
            <h1>Certificado Válido</h1>
            <h2>Este documento es auténtico.</h2>

            <div class="detail-row">
                <span class="detail-label">Participante</span>
                <span class="detail-value">{{ $participant->par_nombre }} {{ $participant->par_apellido }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Curso Aprobado</span>
                <span class="detail-value">{{ $course->cur_nombre }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Fecha de Finalización</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                </span>
            </div>

            <div class="verification-code">
                Ref: {{ $validationCode }}
            </div>

            <div class="footer">
                Sistema de Validación de Certificados
            </div>
        </div>
    @else
        <div class="validation-card invalid">
            <div class="icon-container error-icon">✕</div>
            <h1>Certificado Inválido</h1>
            <h2>{{ $message ?? 'No se ha podido verificar la autenticidad del documento.' }}</h2>

            <div class="footer">
                Si cree que esto es un error, contacte a la administración.
            </div>
        </div>
    @endif

</body>

</html>