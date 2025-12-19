@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('admin.relator.my_courses') }}" class="text-indigo-600 hover:text-indigo-800 mb-4 inline-block">
            <i class="fas fa-arrow-left"></i> Volver a Mis Cursos
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Alumnos Inscritos</h1>
        <p class="text-gray-600">{{ $curso->cur_nombre }}</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @foreach($programas as $programa)
        <div class="mb-8 bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-700">
                        <i class="fa fa-calendar-alt mr-2 text-indigo-500"></i>
                        Sesión del {{ \Carbon\Carbon::parse($programa->pro_inicia)->locale('es')->translatedFormat('d \d\e F \d\e Y') }} 
                        al {{ \Carbon\Carbon::parse($programa->pro_finaliza)->locale('es')->translatedFormat('d \d\e F \d\e Y') }}
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">
                        @if($programa->pro_horario) <i class="fa fa-clock mr-1"></i> {{ $programa->pro_horario }} @endif
                        @if($programa->pro_lugar) <span class="mx-2">|</span> <i class="fa fa-map-marker-alt mr-1"></i> {{ $programa->pro_lugar }} @endif
                        <span class="mx-2">|</span> <i class="fa fa-hourglass-half mr-1"></i> {{ $curso->cur_horas }} Horas
                    </p>
                </div>
                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                    ID: {{ $programa->pro_id }}
                </span>
            </div>

            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            RUT / Login
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Fecha Inscripción
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $students = $inscripciones->get($programa->pro_id);
                    @endphp

                    @if($students && $students->count() > 0)
                        @foreach($students as $inscripcion)
                            <tr>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <div class="flex items-center">
                                        <div class="ml-3">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ $inscripcion->participante->par_nombre ?? 'N/A' }} {{ $inscripcion->participante->par_apellidos ?? '' }}
                                            </p>
                                            <p class="text-gray-500 text-xs">{{ $inscripcion->participante->par_email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $inscripcion->par_login }}</p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ \Carbon\Carbon::parse($inscripcion->ins_date)->format('d/m/Y') }}
                                    </p>
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    @if($inscripcion->informacion && $inscripcion->informacion->inf_estado == 1)
                                        <span class="relative inline-block px-3 py-1 font-semibold text-green-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-green-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Aprobado</span>
                                        </span>
                                    @else
                                        <span class="relative inline-block px-3 py-1 font-semibold text-yellow-900 leading-tight">
                                            <span aria-hidden class="absolute inset-0 bg-yellow-200 opacity-50 rounded-full"></span>
                                            <span class="relative">Pendiente</span>
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                    <form action="{{ route('admin.relator.toggle_approval', $inscripcion->ins_id) }}" method="POST">
                                        @csrf
                                        @if($inscripcion->informacion && $inscripcion->informacion->inf_estado == 1)
                                            <input type="hidden" name="estado" value="0">
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                Desaprobar
                                            </button>
                                        @else
                                            <input type="hidden" name="estado" value="1">
                                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">
                                                Aprobar
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center text-gray-500">
                                No hay alumnos inscritos en esta sesión.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    @endforeach

    @if($programas->isEmpty())
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No se encontraron programas asociados a su perfil para este curso.</p>
        </div>
    @endif
</div>
@endsection