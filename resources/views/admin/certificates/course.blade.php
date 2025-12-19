<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Certificado de Aprobación</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .border-pattern {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border: 10px double #1a365d;
            /* Blue border */
            margin: 20px;
            z-index: -1;
        }

        .corner-decoration {
            position: absolute;
            width: 100px;
            height: 100px;
            border: 2px solid #c05621;
            /* Amber accent */
        }

        .top-left {
            top: 35px;
            left: 35px;
            border-right: none;
            border-bottom: none;
        }

        .top-right {
            top: 35px;
            right: 35px;
            border-left: none;
            border-bottom: none;
        }

        .bottom-left {
            bottom: 35px;
            left: 35px;
            border-right: none;
            border-top: none;
        }

        .bottom-right {
            bottom: 35px;
            right: 35px;
            border-left: none;
            border-top: none;
        }

        .container {
            text-align: center;
            padding-top: 80px;
        }

        .header {
            margin-bottom: 50px;
        }

        .university-name {
            font-size: 24px;
            font-weight: bold;
            color: #1a365d;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .certificate-title {
            font-size: 48px;
            font-family: 'Times New Roman', serif;
            color: #2d3748;
            margin: 20px 0;
            font-style: italic;
        }

        .content {
            margin-bottom: 60px;
        }

        .certifies {
            font-size: 18px;
            color: #718096;
            margin-bottom: 20px;
        }

        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #1a365d;
            border-bottom: 1px solid #cbd5e0;
            display: inline-block;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .course-text {
            font-size: 18px;
            line-height: 1.6;
        }

        .course-name {
            font-weight: bold;
            font-size: 22px;
            color: #2d3748;
        }

        .footer {
            margin-top: 80px;
            width: 100%;
        }

        .signatures {
            width: 80%;
            margin: 0 auto;
        }

        .signature-block {
            width: 40%;
            display: inline-block;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #333;
            width: 80%;
            margin: 0 auto 10px auto;
        }

        .signer-name {
            font-weight: bold;
            font-size: 14px;
        }

        .signer-title {
            font-size: 12px;
            color: #718096;
        }

        .verification {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 10px;
            color: #a0aec0;
        }
    </style>
</head>

<body>
    <div class="border-pattern"></div>
    <div class="corner-decoration top-left"></div>
    <div class="corner-decoration top-right"></div>
    <div class="corner-decoration bottom-left"></div>
    <div class="corner-decoration bottom-right"></div>

    <div class="container">
        <div class="header">
            <div class="university-name">Universidad de Concepción</div>
            <div style="font-size: 14px; color: #718096; letter-spacing: 1px;">DIRECCIÓN DE CAPACITACIÓN</div>
        </div>

        <div class="certificate-title">Certificado de Aprobación</div>

        <div class="content">
            <p class="certifies">Se otorga el presente diploma a</p>

            <div class="student-name">{{ $nombre }}</div>

            <div class="course-text">
                Por haber aprobado satisfactoriamente el curso de<br>
                <span class="course-name">{{ $curso }}</span><br>
                <br>
                Realizado hasta el {{ $fecha_termino }}, con una duración total de {{ $horas }} horas cronológicas.
            </div>
        </div>

        <div class="footer">
            <div class="signatures">
                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signer-name">Coordinador Académico</div>
                    <div class="signer-title">Dirección de Capacitación</div>
                </div>
                <!-- Espacio -->
                <div style="width: 10%; display: inline-block;"></div>

                <div class="signature-block">
                    <div class="signature-line"></div>
                    <div class="signer-name">Director</div>
                    <div class="signer-title">CFRD UdeC</div>
                </div>
            </div>
        </div>

        <div class="verification">
            Código de Verificación: {{ $codigo }} | Generado el {{ date('d/m/Y') }}
        </div>
    </div>
</body>

</html>