@extends('layouts.participant')

@section('title', 'Mis Certificados')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mis Certificados</h1>
            <p class="text-slate-500">Historial de certificados obtenidos como estudiante y docente.</p>
        </div>

        {{-- Certificados como Alumno --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <div
                class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-blue-50 to-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <div>
                        <h2 class="font-bold text-slate-800 text-lg">Como Participante</h2>
                        <p class="text-xs text-slate-500">Cursos aprobados</p>
                    </div>
                </div>
                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                    {{ $studentCertificates->count() }}
                </span>
            </div>

            @if($studentCertificates->count() > 0)
                <div class="divide-y divide-slate-100">
                    @foreach($studentCertificates as $cert)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                            <div class="flex items-start gap-4">
                                <i class="fas fa-award text-amber-400 text-2xl mt-1"></i>
                                <div>
                                    <h3 class="font-semibold text-slate-800 group-hover:text-blue-600 transition-colors">
                                        {{ $cert->course_name }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        Emitido el: {{ \Carbon\Carbon::parse($cert->date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ $cert->download_url }}" target="_blank"
                                class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-2 rounded-lg transition-all flex items-center gap-2 font-bold text-sm">
                                <i class="fas fa-download"></i> <span class="hidden sm:inline">Descargar</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-slate-500">
                    <p>No tienes certificados de participación disponibles aún.</p>
                </div>
            @endif
        </div>

        {{-- Certificados como Docente (Solo si existen) --}}
        @if($teacherCertificates->count() > 0)
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                <div
                    class="p-6 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-purple-50 to-white">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800 text-lg">Como Docente</h2>
                            <p class="text-xs text-slate-500">Actividades de docencia certificadas</p>
                        </div>
                    </div>
                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold">
                        {{ $teacherCertificates->count() }}
                    </span>
                </div>

                <div class="divide-y divide-slate-100">
                    @foreach($teacherCertificates as $cert)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                            <div class="flex items-start gap-4">
                                <i class="fas fa-certificate text-purple-400 text-2xl mt-1"></i>
                                <div>
                                    <h3 class="font-semibold text-slate-800 group-hover:text-purple-600 transition-colors">
                                        {{ $cert->course_name }}
                                    </h3>
                                    <p class="text-sm text-slate-500">
                                        Certificado el: {{ \Carbon\Carbon::parse($cert->date)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ $cert->download_url }}" target="_blank"
                                class="text-purple-600 hover:text-purple-800 hover:bg-purple-50 p-2 rounded-lg transition-all flex items-center gap-2 font-bold text-sm">
                                <i class="fas fa-download"></i> <span class="hidden sm:inline">Descargar</span>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection