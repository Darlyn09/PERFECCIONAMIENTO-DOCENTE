@extends('layouts.participant')

@section('content')
<div class="space-y-6">
    <div class="mb-6">
        <a href="{{ route('participant.relator.my_courses') }}" class="text-blue-600 hover:text-blue-800 mb-4 inline-flex items-center transition-colors font-medium">
            <i class="fas fa-arrow-left mr-2"></i> Volver a Panel Docente
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between mt-2">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Gestión de Alumnos</h1>
                <p class="text-gray-600 mt-1">Curso: <span class="font-semibold">{{ $curso->cur_nombre }}</span></p>
            </div>
            <div class="mt-4 md:mt-0">
                <span class="bg-blue-100 text-blue-800 text-sm font-medium px-4 py-2 rounded-lg">
                    Total: {{ $inscripciones->flatten()->count() }} alumnos
                </span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-r shadow-sm" role="alert">
            <p class="font-bold flex items-center"><i class="fa fa-check-circle mr-2"></i> Operación Exitosa</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @foreach($programas as $programa)
        <div class="mb-8 bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">
                        <i class="fa fa-calendar-alt mr-2 text-blue-500"></i>
                        Sesión del {{ \Carbon\Carbon::parse($programa->pro_inicia)->locale('es')->translatedFormat('d \d\e F \d\e Y') }} 
                        al {{ \Carbon\Carbon::parse($programa->pro_finaliza)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($programa->pro_horario) <i class="fa fa-clock mr-1"></i> {{ $programa->pro_horario }} @endif
                        @if($programa->pro_lugar) <span class="mx-2">|</span> <i class="fa fa-map-marker-alt mr-1"></i> {{ $programa->pro_lugar }} @endif
                        <span class="mx-2">|</span> <i class="fa fa-hourglass-half mr-1"></i> {{ $curso->cur_horas }} Horas
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('participant.relator.program_grades', $programa->pro_id) }}" 
                       class="inline-flex items-center px-3 py-1 bg-white border border-blue-200 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-50 transition-colors">
                        <i class="fa fa-star mr-1.5"></i> Calificar
                    </a>
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                        ID: {{ $programa->pro_id }}
                    </span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b border-gray-200 bg-white text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Participante
                            </th>
                            <th class="px-5 py-3 border-b border-gray-200 bg-white text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Identificación
                            </th>
                            <th class="px-5 py-3 border-b border-gray-200 bg-white text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Fecha Inscripción
                            </th>
                            <th class="px-5 py-3 border-b border-gray-200 bg-white text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Estado Aprobación
                            </th>
                            <th class="px-5 py-3 border-b border-gray-200 bg-white text-center text-xs font-bold text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php 
                            $students = $inscripciones->get($programa->pro_id);
                        @endphp

                        @if($students && $students->count() > 0)
                            @foreach($students as $inscripcion)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-5 py-4 whitespace-no-wrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-400 to-gray-500 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($inscripcion->participante->par_nombre ?? 'U', 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $inscripcion->participante->par_nombre ?? 'N/A' }} {{ $inscripcion->participante->par_apellidos ?? '' }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $inscripcion->participante->par_email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-no-wrap text-sm text-gray-600">
                                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-mono">
                                            {{ $inscripcion->par_login }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-no-wrap text-sm text-gray-600">
                                        {{ \Carbon\Carbon::parse($inscripcion->ins_date)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-no-wrap text-center">
                                        @if($inscripcion->informacion && $inscripcion->informacion->inf_estado == 1)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 bg-green-600 rounded-full mr-1.5"></span>
                                                Aprobado
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 bg-yellow-600 rounded-full mr-1.5"></span>
                                                Pendiente
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 whitespace-no-wrap text-center">
                                        <form action="{{ route('participant.relator.toggle_approval', $inscripcion->ins_id) }}" method="POST">
                                            @csrf
                                            @php
                                                // Lógica para determinar si el curso finalizó
                                                $cursoFinalizado = $curso->cur_fecha_termino && \Carbon\Carbon::parse($curso->cur_fecha_termino)->isPast();
                                                $estado = $inscripcion->informacion->inf_estado ?? 0;
                                            @endphp

                                            @if($estado == 1)
                                                <input type="hidden" name="estado" value="0">
                                                @if($cursoFinalizado)
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 text-red-700 font-bold rounded-lg text-xs hover:bg-red-50 hover:border-red-400 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <i class="fa fa-ban mr-1.5"></i> Deshabilitar Certificado
                                                    </button>
                                                @else
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-white border border-red-300 text-red-700 font-bold rounded-lg text-xs hover:bg-red-50 hover:border-red-400 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <i class="fa fa-times mr-1.5"></i> Revocar
                                                    </button>
                                                @endif
                                            @else
                                                <input type="hidden" name="estado" value="1">
                                                @if($cursoFinalizado)
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent text-white font-bold rounded-lg text-xs hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm">
                                                        <i class="fa fa-certificate mr-1.5"></i> Habilitar Certificado
                                                    </button>
                                                @else
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 border border-transparent text-white font-bold rounded-lg text-xs hover:bg-emerald-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 shadow-sm">
                                                        <i class="fa fa-check mr-1.5"></i> Aprobar
                                                    </button>
                                                @endif
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fa fa-users-slash text-gray-300 text-4xl mb-3"></i>
                                        <p class="text-lg font-medium text-gray-900">No hay alumnos inscritos en esta sesión</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    @if($programas->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No se encontraron sesiones asignadas a usted para este curso.
                    </p>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection