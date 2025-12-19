@extends('layouts.participant')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Panel de Relator</h1>
                <p class="text-gray-600">Gestiona tus cursos asignados y alumnos.</p>
            </div>
            <a href="{{ route('participant.relator.create_course') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition shadow-sm font-semibold text-sm">
                <i class="fa fa-plus mr-2"></i> Crear Nuevo Curso
            </a>
        </div>

        @if($eventos->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-6 text-center border border-gray-200">
                <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa fa-chalkboard text-blue-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-800">Sin cursos asignados</h3>
                <p class="text-gray-500">No tienes cursos asignados como relator actualmente.</p>
            </div>
        @else
            <div class="space-y-10">
                @foreach($eventos as $evento)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <!-- Event Header -->
                        <div
                            class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                            <div>
                                <span class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1 block">Evento</span>
                                <h2 class="text-xl font-bold text-gray-800">{{ $evento->eve_nombre }}</h2>
                            </div>
                            @if($evento->eve_tipo)
                                <span
                                    class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-xs font-medium border border-gray-200">
                                    {{ $evento->eve_tipo }}
                                </span>
                            @endif
                        </div>

                        <!-- Courses Grid -->
                        <div class="p-6 bg-gray-50/50">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($evento->cursos as $curso)
                                    <div
                                        class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 hover:shadow-md transition-shadow group h-full flex flex-col">
                                        <div class="h-2 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                                        <div class="p-6 flex-1 flex flex-col">
                                            <div class="flex items-center justify-between mb-4">
                                                <span
                                                    class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold uppercase tracking-wide">
                                                    {{ $curso->categoria->cat_nombre ?? 'General' }}
                                                </span>
                                                <span class="text-xs text-gray-400 font-medium flex items-center">
                                                    <i class="fas fa-calendar-alt mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($curso->cur_fecha_inicio)->format('d M, Y') }}
                                                </span>
                                            </div>

                                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-2"
                                                title="{{ $curso->cur_nombre }}">
                                                {{ $curso->cur_nombre }}
                                            </h3>

                                            {{-- Mostrar Ubicación/Horario si existe --}}
                                            @php
                                                $programa = $curso->programas->first();
                                            @endphp
                                            @if($programa && $programa->pro_lugar && ($curso->cur_modalidad == 1 || $curso->cur_modalidad == 4))
                                                <div class="text-xs text-gray-500 mb-2 flex items-start">
                                                    <i class="fa fa-map-marker-alt text-red-500 mt-0.5 mr-1.5"></i>
                                                    <span>{{ $programa->pro_lugar }}</span>
                                                </div>
                                            @endif

                                            <p class="text-gray-500 text-sm mb-6 line-clamp-3">
                                                {{ $curso->cur_descripcion ?? 'Sin descripción disponible.' }}
                                            </p>

                                            <div class="flex flex-wrap gap-2 mb-4">
                                                @if($curso->cur_link)
                                                    @if($curso->cur_modalidad == 2) <!-- Online -->
                                                        <a href="{{ $curso->cur_link }}" target="_blank"
                                                            class="inline-flex items-center px-3 py-2 bg-purple-50 border border-purple-200 text-purple-600 rounded-lg text-xs font-semibold hover:bg-purple-100 transition-colors"
                                                            title="Ir a Sala Online">
                                                            <i class="fa fa-video mr-1"></i> Clase
                                                        </a>
                                                    @elseif($curso->cur_modalidad == 1) <!-- Presencial -->
                                                        <a href="{{ $curso->cur_link }}" target="_blank"
                                                            class="inline-flex items-center px-3 py-2 bg-green-50 border border-green-200 text-green-600 rounded-lg text-xs font-semibold hover:bg-green-100 transition-colors"
                                                            title="Ver Ubicación en Mapa">
                                                            <i class="fa fa-map-marked-alt mr-1"></i> Mapa
                                                        </a>
                                                    @else
                                                        <!-- Híbrido (4) u otro -->
                                                        <a href="{{ $curso->cur_link }}" target="_blank"
                                                            class="inline-flex items-center px-3 py-2 bg-blue-50 border border-blue-200 text-blue-600 rounded-lg text-xs font-semibold hover:bg-blue-100 transition-colors"
                                                            title="Link de Conexión">
                                                            <i class="fa fa-link mr-1"></i> Link
                                                        </a>
                                                    @endif
                                                @endif

                                            </div>

                                            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-center">
                                                <div class="text-sm text-gray-500">
                                                    <i class="fa fa-users mr-1"></i>
                                                    <strong>{{ $curso->inscripciones->count() }}</strong> Alumnos
                                                </div>
                                                <a href="{{ route('participant.relator.course_students', $curso->cur_id) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-white border border-blue-200 text-blue-600 rounded-lg font-semibold text-xs uppercase tracking-widest hover:bg-blue-50 transition-colors">
                                                    Gestionar <i class="fa fa-arrow-right ml-2"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $eventos->links() }}
            </div>
        @endif
    </div>
@endsection