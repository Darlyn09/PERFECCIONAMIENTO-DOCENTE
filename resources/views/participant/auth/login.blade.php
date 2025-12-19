<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Participante - SPD UdeC</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Fondo animado sutil con azul y amarillo */
        .animated-bg {
            background: linear-gradient(135deg,
                    #1e3a8a 0%,
                    #1e40af 20%,
                    #1e3a8a 40%,
                    #2563eb 50%,
                    #1e3a8a 60%,
                    #92700c 80%,
                    #1e3a8a 100%);
            background-size: 300% 300%;
            animation: gradientBG 30s ease infinite;
        }

        @keyframes gradientBG {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        /* Partículas muy sutiles */
        .floating-shapes {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            animation: float 40s infinite ease-in-out;
        }

        .shape.blue {
            background: rgba(59, 130, 246, 0.05);
        }

        .shape.gold {
            background: rgba(251, 191, 36, 0.04);
        }

        .shape:nth-child(1) {
            width: 400px;
            height: 400px;
            left: -10%;
            top: -15%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 350px;
            height: 350px;
            right: -10%;
            bottom: -15%;
            animation-delay: -15s;
        }

        .shape:nth-child(3) {
            width: 200px;
            height: 200px;
            right: 15%;
            top: 10%;
            animation-delay: -25s;
        }

        .shape:nth-child(4) {
            width: 180px;
            height: 180px;
            left: 10%;
            bottom: 15%;
            animation-delay: -35s;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0) translateX(0) scale(1);
            }

            25% {
                transform: translateY(-15px) translateX(10px) scale(1.02);
            }

            50% {
                transform: translateY(0) translateX(0) scale(1);
            }

            75% {
                transform: translateY(15px) translateX(-10px) scale(0.98);
            }
        }

        /* Card elegante */
        .login-card {
            background: rgba(255, 255, 255, 0.97);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Input focus sutil */
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.12);
        }

        /* Línea dorada animada */
        .gold-line {
            background: linear-gradient(90deg, #b8860b, #fbbf24, #daa520, #fbbf24, #b8860b);
            background-size: 200% 100%;
            animation: shimmerGold 8s linear infinite;
        }

        @keyframes shimmerGold {
            0% {
                background-position: 200% 0;
            }

            100% {
                background-position: -200% 0;
            }
        }
    </style>
</head>

<body class="animated-bg min-h-screen flex items-center justify-center p-4">
    <!-- Formas flotantes sutiles -->
    <div class="floating-shapes">
        <div class="shape blue"></div>
        <div class="shape gold"></div>
        <div class="shape blue"></div>
        <div class="shape gold"></div>
    </div>

    <!-- Card de Login -->
    <div class="login-card w-full max-w-md rounded-2xl shadow-2xl overflow-hidden relative z-10">
        <!-- Header con colores UdeC -->
        <div class="relative bg-gradient-to-b from-blue-950 to-blue-900 py-8 text-center">
            <!-- Línea dorada animada -->
            <div class="absolute top-0 left-0 w-full h-1 gold-line"></div>

            <!-- Logo UdeC -->
            <div class="flex flex-col items-center mb-4">
                <div class="w-20 h-20 bg-white rounded-full p-2 shadow-lg flex items-center justify-center mb-3">
                    <img src="https://www.udec.cl/pexterno/sites/default/files/2022-12/logo%20negro.png"
                        alt="Universidad de Concepción" class="w-14 h-14 object-contain"
                        onerror="this.onerror=null; this.parentElement.innerHTML='<svg class=\'w-12 h-12 text-blue-900\' viewBox=\'0 0 24 24\' fill=\'currentColor\'><path d=\'M12 3L1 9l4 2.18v6L12 21l7-3.82v-6l2-1.09V17h2V9L12 3zm6.82 6L12 12.72 5.18 9 12 5.28 18.82 9zM17 15.99l-5 2.73-5-2.73v-3.72L12 15l5-2.73v3.72z\'/></svg>';">
                </div>
                <!-- CAMBIO: Título -->
                <h1 class="text-xl font-semibold text-white">Portal del Participante</h1>
                <p class="text-amber-300/80 text-sm mt-0.5">Sistema SPD</p>
            </div>

            <!-- Escudo decorativo sutil -->
            <div
                class="absolute bottom-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/30 to-transparent">
            </div>
        </div>

        <!-- Formulario -->
        <div class="p-8">
            <h2 class="text-lg font-medium text-slate-700 text-center mb-6">
                Acceso Estudiantes
            </h2>

            <!-- CAMBIO: Ruta -->
            <form method="POST" action="{{ route('participant.login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-slate-600 text-sm font-medium mb-2" for="par_correo">
                        Correo Electrónico
                    </label>
                    <input
                        class="input-glow w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-900 focus:bg-white transition-all duration-300 @error('par_correo') border-red-400 bg-red-50 @enderror"
                        id="par_correo" type="email" name="par_correo" value="{{ old('par_correo') }}" required
                        autofocus placeholder="ejemplo@udec.cl">
                    @error('par_correo')
                        <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-slate-600 text-sm font-medium mb-2" for="password">
                        Contraseña
                    </label>
                    <input
                        class="input-glow w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:border-blue-900 focus:bg-white transition-all duration-300"
                        id="password" type="password" name="password" required placeholder="••••••••">
                </div>

                <button
                    class="w-full bg-gradient-to-r from-blue-900 to-blue-800 hover:from-blue-800 hover:to-blue-700 text-white font-medium py-2.5 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-900 focus:ring-offset-2 transition-all duration-300 shadow-md hover:shadow-lg"
                    type="submit">
                    Ingresar
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100">
                <p class="text-sm text-center text-slate-500 mb-3">¿Eres administrador?</p>
                <a href="{{ route('login') }}"
                    class="block w-full text-center py-2.5 px-4 rounded-lg border-2 border-slate-300 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-800 transition-colors duration-300">
                    Ir al Acceso Administrativo
                </a>
            </div>
        </div>

        <!-- Footer con colores UdeC -->
        <div
            class="bg-gradient-to-r from-slate-50 via-amber-50/30 to-slate-50 px-8 py-3 border-t border-slate-100 text-center">
            <p class="text-xs text-slate-500">
                <span class="text-blue-900 font-medium">Universidad de Concepción</span> · Sistema SPD 2025
            </p>
        </div>
    </div>
</body>

</html>