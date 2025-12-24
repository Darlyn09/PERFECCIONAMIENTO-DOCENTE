<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista Previa</title>
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            /* Dark slate background */
            background-color: #1f2937;
            margin: 0;
            padding: 0;
            overflow: hidden;
            width: 100vw;
            height: 100vh;
            position: relative;
            font-family: sans-serif;
        }

        /* Certificate Container: Positioned Manually via JS */
        #cert-container {
            position: absolute;
            top: 0;
            left: 0;
            transform-origin: 0 0;
            box-shadow: 0 0 100px rgba(0, 0, 0, 0.7);
            background: white;
            transition: transform 0.1s ease-out, top 0.1s ease-out, left 0.1s ease-out;

            /* Vertical Center Content Logic */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        /* Toolbar: Floating, Higher Up, High Contrast */
        #ui-layer {
            position: fixed;
            bottom: 15%;
            /* Raised significantly */
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            pointer-events: none;
            z-index: 9999;
        }

        #toolbar {
            pointer-events: auto;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 99px;
            padding: 10px 20px;
            display: flex;
            gap: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        /* High Visibility Buttons */
        button {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid white;
            background: #2563eb;
            /* Bright Blue */
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s, background 0.2s;
        }

        button:hover {
            background: #1d4ed8;
            transform: scale(1.1);
        }

        button:active {
            transform: scale(0.9);
        }

        button.primary {
            background: #10b981;
            /* Green */
            width: 60px;
            height: 60px;
            font-size: 24px;
        }

        button.primary:hover {
            background: #059669;
        }

        {!! $css !!}
    </style>
</head>

<body>

    <div id="cert-container" style="width:{{ $width }}px; height:{{ $height }}px;">
        {!! $html !!}
    </div>

    <!-- Controls -->
    <div id="ui-layer">
        <div id="toolbar">
            <button onclick="zoom(-0.1)" title="Alejar"><i class="fas fa-minus"></i></button>
            <button onclick="centerAndFit()" class="primary" title="CENTRAR"><i
                    class="fas fa-compress-arrows-alt"></i></button>
            <button onclick="zoom(0.1)" title="Acercar"><i class="fas fa-plus"></i></button>
        </div>
    </div>

    <script>
        // FAIL-SAFE SCRIPT

        window.docW = {{ $width }};
        window.docH = {{ $height }};
        window.currentScale = 1;
        window.container = document.getElementById('cert-container');

        window.updateLayout = function () {
            var winW = window.innerWidth;
            var winH = window.innerHeight;

            if (!window.container) window.container = document.getElementById('cert-container');

            if (window.container) {
                // Dimensions
                var finalW = window.docW * window.currentScale;
                var finalH = window.docH * window.currentScale;

                // Absolute Center Calculation
                var left = (winW - finalW) / 2;
                var top = (winH - finalH) / 2;

                // Apply
                window.container.style.transform = 'scale(' + window.currentScale + ')';
                window.container.style.left = left + 'px';
                window.container.style.top = top + 'px';

                // Force Flex Display (Vertical Center Content)
                window.container.style.display = 'flex';
                window.container.style.flexDirection = 'column';
                window.container.style.justifyContent = 'center';
                window.container.style.alignItems = 'center';
            }
        };

        window.centerAndFit = function () {
            var winW = window.innerWidth;
            var winH = window.innerHeight;

            // Calculate ratios
            var ratioW = winW / window.docW;
            var ratioH = winH / window.docH;

            // Fit to 85% of screen
            window.currentScale = Math.min(ratioW, ratioH) * 0.85;

            window.updateLayout();
        };

        window.zoom = function (delta) {
            window.currentScale += delta;
            if (window.currentScale < 0.1) window.currentScale = 0.1;
            window.updateLayout();
        };

        // Init immediately
        window.centerAndFit();

        // Bind resize
        window.addEventListener('resize', window.updateLayout);
    </script>

</body>

</html>