@extends('layouts.participant')

@section('content')
    {{-- Filter Logic: Active = State 1 AND Not Approved --}}
    @php
        $activeCoursesList = $enrolledCourses->filter(function ($c) {
            return $c->cur_estado == 1 && !$c->is_approved;
        });
        $finishedCoursesList = $enrolledCourses->filter(function ($c) {
            return $c->cur_estado != 1 || $c->is_approved;
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
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
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
                            <div
                                class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group">
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
                                    <p class="text-slate-500 text-sm mb-6 line-clamp-3 leading-relaxed">
                                        {{ $course->cur_descripcion ?? 'Sin descripción disponible para este curso.' }}
                                    </p>

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
                                                <a href="{{ route('participant.certificates.download', $course->cur_id) }}"
                                                    target="_blank"
                                                    class="group relative flex items-center justify-center w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg overflow-hidden">
                                                    <span class="relative z-10 flex items-center">
                                                        <i class="fa fa-certificate mr-2"></i> Descargar Certificado
                                                    </span>
                                                </a>
                                            @endif

                                            <a href="#"
                                                class="group relative flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition-all shadow-md hover:shadow-lg overflow-hidden">
                                                <span class="relative z-10 flex items-center">
                                                    <i
                                                        class="fa fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                                                </span>
                                                <div
                                                    class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                                </div>
                                            </a>
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
                                                <a href="{{ route('participant.certificates.download', $course->cur_id) }}"
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
    </div>
@endsection