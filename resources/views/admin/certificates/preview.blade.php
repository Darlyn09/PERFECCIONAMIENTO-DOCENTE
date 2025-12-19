<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa Certificado</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #555;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .certificate-wrapper {
            background-color: white;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            /* Dimensiones dinámicas */
            width:
                {{ $width }}
                px;
            height:
                {{ $height }}
                px;
            position: relative;
            overflow: hidden;
        }

        /* Estilos Inyectados */
        {!! $css !!}
    </style>
</head>

<body>

    <div class="certificate-wrapper">
        {!! $html !!}
    </div>

</body>

</html>