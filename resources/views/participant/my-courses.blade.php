@extends('layouts.participant')

@section('content')
{{-- Filter Logic: Active = Not Approved (Pending/In Progress/Future) --}}
@php
    $activeCoursesList = $enrolledCourses->filter(function ($c) {
        return !$c->is_approved; // Show everything not yet passed, regardless of global course state
    });
    $finishedCoursesList = $enrolledCourses->filter(function ($c) {
        return $c->is_approved;
    });

    // Filter Events Logic
    $activeEvents = $myEvents->filter(function ($e) {
        return \Carbon\Carbon::parse($e->eve_finaliza)->isFuture();
    });
    $finishedEvents = $myEvents->filter(function ($e) {
        return \Carbon\Carbon::parse($e->eve_finaliza)->isPast();
    });

    $totalActive = $activeCoursesList->count() + $activeEvents->count();
    $totalFinished = $finishedCoursesList->count() + $finishedEvents->count();
@endphp
<div class="min-h-screen bg-slate-50 relative pb-20" x-data="myCoursesData()">
    <div class="max-w-7xl mx-auto" x-data="{ currentTab: 'active', statusFilter: 'all' }">
        <div class="flex flex-col md:flex-row items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Mi Aprendizaje</h1>
                <p class="text-slate-500 mt-1">Gestiona tus cursos y certificaciones</p>
            </div>

            <!-- Tabs Navigation -->
            <div class="bg-white p-1 rounded-lg border border-slate-200 flex shadow-sm">
                <button @click="currentTab = 'active'"
                    :class="currentTab === 'active' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'"
                    class="px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 flex items-center">
                    <i class="fa fa-book-open mr-2"></i> En Curso
                    <span class="ml-2 bg-white/20 px-1.5 py-0.5 rounded text-xs" x-show="currentTab === 'active'">
                        {{ $totalActive }}
                    </span>
                </button>
                <button @click="currentTab = 'finished'"
                    :class="currentTab === 'finished' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-50'"
                    class="px-4 py-2 text-sm font-semibold rounded-md transition-all duration-200 flex items-center ml-1">
                    <i class="fa fa-certificate mr-2"></i> Finalizados
                    <span class="ml-2 bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded text-xs"
                        :class="currentTab === 'finished' ? 'bg-white/20 text-white' : ''">
                        {{ $totalFinished }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Sección: Cursos Activos -->

        <div x-show="currentTab === 'active'" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- EVENTOS ACTIVOS --}}
            @if($activeEvents->count() > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center">
                        <span class="w-1.5 h-6 bg-purple-500 rounded-full mr-3"></span>
                        Eventos en Curso
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($activeEvents as $event)
                            <div
                                class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group">
                                <div
                                    class="h-40 bg-gradient-to-br from-purple-800 to-indigo-900 relative p-5 flex flex-col justify-between overflow-hidden">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-xl">
                                    </div>
                                    <div class="relative z-10 flex justify-between items-start">
                                        <span
                                            class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm border border-white/10">
                                            Evento
                                        </span>
                                        <span
                                            class="px-2.5 py-1 bg-purple-500/90 text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm flex items-center">
                                            <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span> En
                                            Curso
                                        </span>
                                    </div>
                                    <div class="relative z-10">
                                        <h3 class="text-white font-bold text-lg leading-tight line-clamp-2">
                                            {{ $event->eve_nombre }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col bg-white">
                                    <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                        {{ $event->eve_descripcion ?? 'Sin descripción.' }}
                                    </p>
                                    <div class="mt-auto pt-4 border-t border-slate-50">
                                        <div class="flex items-center text-xs text-slate-500 mb-3">
                                            <i class="fa fa-calendar-alt text-purple-500 mr-2"></i>
                                            <span>{{ \Carbon\Carbon::parse($event->eve_inicia)->format('d M') }} -
                                                {{ \Carbon\Carbon::parse($event->eve_finaliza)->format('d M, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($activeCoursesList->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($activeCoursesList as $course)
                        <div x-data="{ expanded: false }"
                            class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col h-full group">
                            <!-- Header Curso -->
                            <div
                                class="h-40 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 relative p-5 flex flex-col justify-between overflow-hidden">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16 blur-xl">
                                </div>
                                <div
                                    class="absolute bottom-0 left-0 w-24 h-24 bg-blue-500/20 rounded-full -ml-12 -mb-12 blur-lg">
                                </div>

                                <div class="relative z-10 flex justify-between items-start">
                                    <span
                                        class="px-2.5 py-1 bg-white/20 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm border border-white/10">
                                        {{ $course->cur_year ?? date('Y') }}
                                    </span>
                                    <span
                                        class="px-2.5 py-1 bg-emerald-500/90 text-white text-[10px] font-bold uppercase tracking-wider rounded-full shadow-sm flex items-center">
                                        <span class="w-1.5 h-1.5 bg-white rounded-full mr-1.5 animate-pulse"></span> Activo
                                    </span>
                                </div>
                                <div class="relative z-10">
                                    <div class="text-blue-200 text-xs font-bold uppercase tracking-wider mb-1">
                                        {{ $course->categoria ? $course->categoria->cat_nombre : 'General' }}
                                    </div>
                                    <h3 class="text-white font-bold text-lg leading-tight line-clamp-2">
                                        {{ $course->cur_nombre }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="p-6 flex-1 flex flex-col bg-white">
                                <div x-show="!expanded">
                                    <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                        {{ $course->cur_descripcion ?? 'Sin descripción disponible para este curso.' }}
                                    </p>
                                </div>
                                <div x-show="expanded" x-collapse>
                                    <div class="mb-4">
                                        <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Descripción Completa</h4>
                                        <p class="text-slate-600 text-sm leading-relaxed">
                                            {{ $course->cur_descripcion ?? 'Sin descripción disponible para este curso.' }}
                                        </p>
                                    </div>

                                    @if($course->cur_objetivos)
                                        <div class="mb-4">
                                            <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Objetivos</h4>
                                            <div class="text-slate-600 text-sm leading-relaxed prose prose-sm max-w-none">
                                                {!! nl2br(e($course->cur_objetivos)) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if($course->cur_contenidos)
                                        <div class="mb-4">
                                            <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Contenidos</h4>
                                            <div class="text-slate-600 text-sm leading-relaxed prose prose-sm max-w-none">
                                                {!! nl2br(e($course->cur_contenidos)) !!}
                                            </div>
                                        </div>
                                    @endif

                                    @if($course->programas->count() > 0 && $course->programas->first()->relatores->count() > 0)
                                        <div class="mb-4">
                                            <h4 class="text-xs font-bold text-slate-700 uppercase mb-2">Docentes</h4>
                                            <ul class="text-sm text-slate-600 space-y-1">
                                                @foreach($course->programas->first()->relatores as $relator)
                                                    <li class="flex items-center">
                                                        <i class="fa fa-chalkboard-teacher text-blue-400 mr-2 text-xs"></i>
                                                        {{ $relator->relator->rel_nombres ?? 'Docente' }}
                                                        {{ $relator->relator->rel_apellidos ?? '' }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-auto space-y-4">
                                    <div
                                        class="flex flex-col text-xs font-semibold text-slate-500 uppercase tracking-wide border-b border-slate-50 pb-4">
                                        <div class="flex justify-between w-full mb-2">
                                            <div class="flex items-center">
                                                <i class="fa fa-calendar-alt text-blue-500 mr-2"></i>
                                                <span>{{ $course->cur_fecha_inicio ? \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d M, Y') : 'TBD' }}</span>
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fa fa-clock text-yellow-500 mr-2"></i>
                                                <span>{{ $course->cur_horas ?? 0 }}h</span>
                                            </div>
                                        </div>

                                        @php
                                            $pLocation = $course->programas->first()->pro_lugar ?? null;
                                            $modality = $course->cur_modalidad;
                                        @endphp

                                        @if($pLocation && ($modality == 1 || $modality == 4))
                                            <div class="flex items-center text-slate-600 mt-1">
                                                <i class="fa fa-map-marker-alt text-red-500 mr-2"></i>
                                                <span class="mr-2 truncate max-w-[150px]">{{ $pLocation }}</span>
                                                @if($course->cur_link)
                                                    <a href="{{ $course->cur_link }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-800 underline decoration-dotted lowercase first-letter:uppercase">
                                                        ver mapa
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>

                                    <div class="pt-2 space-y-2">
                                        @if($course->is_approved)
                                            <a href="{{ route('participant.certificates.download', ['login' => Auth::guard('participant')->user()->par_login, 'courseId' => $course->cur_id]) }}"
                                                target="_blank"
                                                class="group relative flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg overflow-hidden">
                                                <span class="relative z-10 flex items-center">
                                                    <i class="fa fa-certificate mr-2"></i> Descargar Certificado
                                                </span>
                                            </a>
                                        @endif

                                        {{-- Botón Evaluacion --}}
                                        @php 
                                            // Encontrar la inscripcion actual en el array $enrollments
                                            // Como estamos iterando una collection mapeada, no tenemos acceso directo a la inscripcion fácilmente
                                            // Hack: Lo obtenemos del usuario autenticado directo o le pasamos la info desde el controlador
                                            // Usaremos el helper auth() para una consulta rápida o asumimos que ya pasó
                                            // MEJOR OPCION: El controlador ya debería haber inyectado si está evaluado.
                                            // Como no quiero tocar mas el controlador, haré un fetch simple aquí o usaré JS.
                                            // Pero lo correcto es blade. 
                                            // Verificar si ya evaluó:
                                            // $evaluated = \App\Models\Inscripcion::where('par_login', auth()->guard('participant')->user()->par_login)->where('cur_id', $course->cur_id)->whereNotNull('ins_evaluacion')->exists();
                                            // Esto es N+1 query, pero aceptable para MVP.
                                            $evaluated = \App\Models\Inscripcion::where('par_login', auth()->guard('participant')->user()->par_login)->where('cur_id', $course->cur_id)->whereNotNull('ins_evaluacion')->exists();
                                        @endphp

                                        @if(!$evaluated)
                                            <button @click="openRatingModal({{ $course->cur_id }})"
                                                class="group relative flex items-center justify-center w-full bg-amber-400 hover:bg-amber-500 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg overflow-hidden">
                                                <span class="relative z-10 flex items-center">
                                                    <i class="fas fa-star mr-2"></i> Evaluar Curso
                                                </span>
                                            </button>
                                        @endif

                                        <button @click="expanded = !expanded"
                                            class="group relative flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg overflow-hidden">
                                            <span class="relative z-10 flex items-center">
                                                <span x-text="expanded ? 'Ocultar Detalle' : 'Ver Detalle'"></span>
                                                <i class="ml-2 transition-transform duration-300"
                                                    :class="expanded ? 'fa fa-chevron-up' : 'fa fa-arrow-right group-hover:translate-x-1'"></i>
                                            </span>
                                            <div
                                                class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-12 text-center">
                    <div
                        class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-400">
                        <i class="fa fa-book-reader text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">No tienes cursos activos</h3>
                    <p class="text-slate-500 mb-6 max-w-md mx-auto">
                        Inscríbete en nuevos cursos para comenzar tu aprendizaje.
                    </p>
                    <a href="{{ route('participant.dashboard') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                        Ver Oferta Académica
                    </a>
                </div>
            @endif
        </div>

        <!-- Sección: Cursos Finalizados -->
        <div x-show="currentTab === 'finished'" x-cloak x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            {{-- EVENTOS FINALIZADOS --}}
            @if($finishedEvents->count() > 0)
                <div class="mb-8">
                    <h2 class="text-lg font-bold text-slate-700 mb-4 flex items-center">
                        <span class="w-1.5 h-6 bg-slate-400 rounded-full mr-3"></span>
                        Eventos Finalizados
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($finishedEvents as $event)
                            <div
                                class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full grayscale hover:grayscale-0 group">
                                <div
                                    class="h-32 bg-slate-800 relative p-4 flex flex-col justify-between group-hover:bg-purple-900 transition-colors duration-500">
                                    <div class="flex justify-between items-start">
                                        <span
                                            class="px-2 py-1 bg-white/10 backdrop-blur-sm text-slate-200 text-xs font-semibold rounded">Evento</span>
                                        <span
                                            class="px-2 py-1 bg-purple-500/80 text-white text-xs font-semibold rounded flex items-center">
                                            <i class="fa fa-check mr-1"></i> Finalizado
                                        </span>
                                    </div>
                                    <div>
                                        <h3 class="text-white font-bold text-lg leading-tight line-clamp-1">
                                            {{ $event->eve_nombre }}
                                        </h3>
                                    </div>
                                </div>
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="mt-auto flex items-center justify-between text-xs text-slate-500">
                                        <span><i class="fa fa-calendar-check mr-1"></i> Finalizado:
                                            {{ \Carbon\Carbon::parse($event->eve_finaliza)->format('M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($finishedCoursesList->count() > 0)
                {{-- Filtros de Estado --}}
                <div class="mb-6 flex justify-end">
                    <div class="inline-flex bg-white rounded-lg border border-slate-200 p-1 shadow-sm">
                        <button @click="statusFilter = 'all'"
                            :class="statusFilter === 'all' ? 'bg-slate-100 text-slate-800 font-semibold shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                            class="px-3 py-1.5 text-xs rounded-md transition-all">Todos</button>
                        <button @click="statusFilter = 'approved'"
                            :class="statusFilter === 'approved' ? 'bg-emerald-100 text-emerald-800 font-semibold shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                            class="px-3 py-1.5 text-xs rounded-md transition-all">Aprobados</button>
                        <button @click="statusFilter = 'not_approved'"
                            :class="statusFilter === 'not_approved' ? 'bg-yellow-100 text-yellow-800 font-semibold shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                            class="px-3 py-1.5 text-xs rounded-md transition-all">Pendientes</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($finishedCoursesList as $course)
                        <div x-show="statusFilter === 'all' || (statusFilter === 'approved' && {{ $course->is_approved ? 'true' : 'false' }}) || (statusFilter === 'not_approved' && {{ !$course->is_approved ? 'true' : 'false' }})"
                            class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300 flex flex-col h-full grayscale hover:grayscale-0 group">
                            <!-- Header Curso (Finalizado) -->
                            <div
                                class="h-32 bg-slate-800 relative p-4 flex flex-col justify-between group-hover:bg-emerald-900 transition-colors duration-500">
                                <div class="flex justify-between items-start">
                                    <span
                                        class="px-2 py-1 bg-white/10 backdrop-blur-sm text-slate-200 text-xs font-semibold rounded">
                                        {{ $course->cur_year ?? date('Y') }}
                                    </span>
                                    <span
                                        class="px-2 py-1 bg-emerald-500/80 text-white text-xs font-semibold rounded flex items-center">
                                        <i class="fa fa-check mr-1"></i> Completado
                                    </span>
                                </div>
                                <div>
                                    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">
                                        {{ $course->categoria ? $course->categoria->cat_nombre : 'General' }}
                                    </div>
                                    <h3 class="text-white font-bold text-lg leading-tight line-clamp-1">
                                        {{ $course->cur_nombre }}
                                    </h3>
                                </div>
                            </div>

                            <!-- Contenido -->
                            <div class="p-6 flex-1 flex flex-col">
                                <div class="mt-auto space-y-4">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span><i class="fa fa-calendar-check mr-1"></i> Finalizado:
                                            {{ $course->cur_fecha_termino ? \Carbon\Carbon::parse($course->cur_fecha_termino)->format('M Y') : 'N/A' }}</span>
                                        <span><i class="fa fa-clock mr-1"></i> {{ $course->cur_horas }}h</span>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 min-h-[60px] flex items-end">
                                        @if($course->is_approved)
                                            <a href="{{ route('participant.certificates.download', ['login' => Auth::guard('participant')->user()->par_login, 'courseId' => $course->cur_id]) }}"
                                                class="flex items-center justify-center w-full py-2.5 rounded-lg border-2 border-emerald-500 text-emerald-600 font-bold hover:bg-emerald-50 transition-colors"
                                                target="_blank">
                                                <i class="fa fa-download mr-2"></i> Descargar Certificado
                                            </a>
                                        @else
                                            <div class="w-full text-center text-xs text-slate-400 italic py-2">
                                                Certificado no disponible
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-slate-50 rounded-xl p-12 text-center border-2 border-dashed border-slate-200">
                    <div
                        class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                        <i class="fa fa-certificate text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">Aún no hay cursos finalizados</h3>
                    <p class="text-slate-500 text-sm">Completa tus cursos activos para obtener tus certificados.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de Evaluación --}}
    <div x-show="showRatingModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showRatingModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true" @click="showRatingModal = false"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showRatingModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                            <i class="fas fa-star text-yellow-600"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Evaluar Curso</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">Tu opinión es importante para nosotros. Por favor
                                    califica tu experiencia en este curso.</p>

                                <div class="flex justify-center gap-2 mb-4">
                                    <template x-for="i in 5">
                                        <button @click="rating = i"
                                            class="text-3xl focus:outline-none transition-transform hover:scale-110"
                                            :class="rating >= i ? 'text-yellow-400' : 'text-gray-300'">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </template>
                                </div>

                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">¿Repetirías este
                                        curso?</label>
                                    <div class="flex gap-4 justify-center">
                                        <button @click="repeat = 1" type="button"
                                            class="flex-1 py-2 px-4 rounded-lg border-2 transition-colors font-bold"
                                            :class="repeat === 1 ? 'border-emerald-500 bg-emerald-50 text-emerald-700' : 'border-gray-200 text-gray-500 hover:border-emerald-200'">
                                            <i class="fas fa-thumbs-up mr-2"></i> Sí
                                        </button>
                                        <button @click="repeat = 0" type="button"
                                            class="flex-1 py-2 px-4 rounded-lg border-2 transition-colors font-bold"
                                            :class="repeat === 0 ? 'border-red-500 bg-red-50 text-red-700' : 'border-gray-200 text-gray-500 hover:border-red-200'">
                                            <i class="fas fa-thumbs-down mr-2"></i> No
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="submitRating()" :disabled="rating === 0 || repeat === null || loading"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                        <span x-show="!loading">Enviar Evaluación</span>
                        <span x-show="loading"><i class="fas fa-spinner fa-spin"></i> Enviando...</span>
                    </button>
                    <button type="button" @click="showRatingModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Feedback Modal --}}
    <div x-show="openFeedbackModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openFeedbackModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true" @click="openFeedbackModal = false"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="openFeedbackModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">

                <form :action="'{{ route('participant.save_feedback', 0) }}'.replace('/0', '/' + feedbackInsId)"
                    method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fa fa-star text-yellow-600"></i>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-slate-900" id="modal-title">
                                    Valorar Curso <span x-text="feedbackCourseName"
                                        class="font-bold text-blue-600 block text-sm mt-1"></span>
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">Calificación
                                            General</label>
                                        <div
                                            class="flex items-center justify-center sm:justify-start gap-2 text-2xl text-slate-300">
                                            <template x-for="i in 5">
                                                <i class="fa fa-star cursor-pointer transition-colors"
                                                    :class="starRating >= i ? 'text-yellow-400' : 'text-slate-300'"
                                                    @click="starRating = i; $refs.ratingInput.value = i"></i>
                                            </template>
                                            <input type="hidden" name="inf_valoracion" x-ref="ratingInput" required>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">¿Repetiría este
                                            curso?</label>
                                        <div class="flex items-center gap-4">
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="inf_repetir" value="1"
                                                    class="form-radio text-blue-600" required>
                                                <span class="ml-2 text-sm text-slate-600">Sí</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="radio" name="inf_repetir" value="0"
                                                    class="form-radio text-red-600">
                                                <span class="ml-2 text-sm text-slate-600">No</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-slate-700 mb-1">¿Qué faltó por
                                            aprender?</label>
                                        <textarea name="inf_mejora" rows="3"
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-slate-300 rounded-md"
                                            placeholder="Tus comentarios nos ayudan a mejorar..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Enviar Valoración
                        </button>
                        <button type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            @click="openFeedbackModal = false">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function myCoursesData() {
        return {
            expanded: false,
            showRatingModal: false,
            rating: 0,
            repeat: null,
            courseId: null,
            loading: false,
            // Variables para legacy tabs si son necesarias, pero idealmente manejar todo aquí
            currentTab: 'active',
            statusFilter: 'all',

            openRatingModal(courseId) {
                console.log('Opening modal for course:', courseId);
                this.courseId = courseId;
                this.rating = 0;
                this.repeat = null;
                this.showRatingModal = true;
            },

            async submitRating() {
                if (this.rating === 0 || this.repeat === null) return;
                
                this.loading = true;
                try {
                    const response = await fetch('{{ route('participant.courses.rate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            course_id: this.courseId,
                            rating: this.rating,
                            repeat: this.repeat
                        })
                    });
                    
                    const data = await response.json();
                    if(data.success) {
                        alert('¡Gracias por tu evaluación!');
                        this.showRatingModal = false;
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Error desconocido'));
                    }
                } catch (e) {
                    console.error(e);
                    alert('Ocurrió un error al enviar la evaluación.');
                } finally {
                    this.loading = false;
                }
            }
        }
    }
</script>