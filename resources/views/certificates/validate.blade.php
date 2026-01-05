<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validación de Certificado - CFRD UDEC</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .bg-pattern {
            background-image: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%239C92AC" fill-opacity="0.05"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col items-center justify-center p-4 bg-pattern">

    <div class="max-w-xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">

        {{-- Header Status --}}
        <div class="{{ $isValid ? 'bg-emerald-600' : 'bg-red-600' }} p-8 text-center relative overflow-hidden">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">
            </div>

            <div class="relative z-10">
                <div
                    class="mx-auto bg-white rounded-full w-24 h-24 flex items-center justify-center mb-4 shadow-lg transform transition-transform hover:scale-105 duration-300">
                    <i
                        class="fas {{ $isValid ? 'fa-check-circle text-emerald-600' : 'fa-times-circle text-red-600' }} text-5xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">
                    {{ $isValid ? 'Certificado Auténtico' : 'Documento No Válido' }}
                </h1>
                <p
                    class="{{ $isValid ? 'text-emerald-100' : 'text-red-100' }} font-medium text-sm uppercase tracking-wider">
                    {{ $isValid ? 'Verificado Oficialmente' : 'Error de Validación' }}
                </p>
            </div>
        </div>

        {{-- Content --}}
        <div class="p-8 sm:p-10 space-y-8">
            @if($isValid)
                <div class="text-center space-y-2">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-widest">Certificado Otorgado a</p>
                    <h2 class="text-2xl sm:text-3xl font-bold text-slate-800 leading-tight">
                        {{ mb_strtoupper($participant->par_nombre . ' ' . ($participant->par_apellidos ?? $participant->par_apellido)) }}
                    </h2>
                    <p class="text-slate-500 font-mono text-sm">{{ $participant->par_rut ?? $participant->par_login }}</p>
                </div>

                <div class="relative py-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="flex flex-col items-center justify-center text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-blue-200 transition-colors group">
                        <div
                            class="mb-3 p-3 bg-blue-100 rounded-full group-hover:bg-blue-600 transition-colors duration-300">
                            <i
                                class="fas fa-graduation-cap text-blue-600 group-hover:text-white text-xl transition-colors"></i>
                        </div>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wide mb-1">Curso Aprobado</p>
                        <h3 class="text-lg font-bold text-blue-900 leading-snug">
                            {{ $course->cur_nombre }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Fecha Finalización</p>
                            <p class="text-slate-700 font-bold">
                                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                            <p class="text-[10px] text-slate-400 uppercase font-bold mb-1">Código Validación</p>
                            <p class="text-slate-700 font-mono font-bold text-sm truncate"
                                title="{{ $validationCode ?? 'N/A' }}">
                                {{ $validationCode ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 text-center">
                    <img src="https://cfrd.cl/wp-content/uploads/2020/07/Logo-CFRD-Alta.png" alt="Logo Institucional"
                        class="h-12 mx-auto opacity-80 hover:opacity-100 transition-opacity">
                </div>
            @else
                <div class="text-center space-y-4">
                    <p class="text-slate-600 leading-relaxed">
                        {{ $message ?? 'El código proporcionado no corresponde a un certificado emitido válido o el participante no cumple los requisitos.' }}
                    </p>
                    <div class="p-4 bg-red-50 text-red-700 text-sm rounded-xl border border-red-100">
                        Si considera que esto es un error, por favor contacte a la administración académica.
                    </div>
                </div>
            @endif
        </div>

        {{-- Footer --}}
        <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
            <p class="text-xs text-slate-400 font-medium">
                &copy; {{ date('Y') }} Sistema de Gestión Académica.
            </p>
        </div>
    </div>

</body>

</html>