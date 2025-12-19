<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Certificado</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden">
        @if($isValid)
            <div class="bg-green-600 p-6 text-center">
                <div class="mx-auto bg-white rounded-full w-20 h-20 flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-check text-4xl text-green-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Certificado Válido</h1>
                <p class="text-green-100 text-sm">Verificado por el Sistema</p>
            </div>

            <div class="p-8 space-y-6">
                <div class="text-center">
                    <p class="text-sm text-gray-500 uppercase tracking-wide font-bold mb-1">Otorgado a</p>
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }} {{ $user->lastname ?? '' }}</h2>
                </div>

                <div class="text-center border-t border-b border-gray-100 py-4">
                    <p class="text-sm text-gray-500 uppercase tracking-wide font-bold mb-1">Curso Aprobado</p>
                    <h3 class="text-lg font-semibold text-blue-600">{{ $course->cur_nombre }}</h3>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-500 uppercase tracking-wide font-bold mb-1">Fecha de Finalización</p>
                    <p class="text-gray-700 font-medium">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
                </div>

                <div class="mt-8 pt-4 text-center">
                    <p class="text-xs text-gray-400">Verificación generada el {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        @else
            <div class="bg-red-600 p-6 text-center">
                <div class="mx-auto bg-white rounded-full w-20 h-20 flex items-center justify-center mb-4 shadow-lg">
                    <i class="fas fa-times text-4xl text-red-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-white mb-1">Certificado No Válido</h1>
            </div>
            <div class="p-8 text-center">
                <p class="text-gray-600">{{ $message ?? 'No se pudo verificar la autenticidad de este certificado.' }}</p>
                <a href="/" class="mt-6 inline-block text-blue-600 font-medium hover:underline">Volver al inicio</a>
            </div>
        @endif
    </div>

</body>

</html>