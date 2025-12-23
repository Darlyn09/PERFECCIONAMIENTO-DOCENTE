@extends('layouts.participant')

@section('content')
    <div class="min-h-screen bg-gray-100 -m-4 p-4 sm:p-6 text-gray-800">

        {{-- Header --}}
        <div class="max-w-7xl mx-auto mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Agenda Académica</h1>
                    <p class="text-gray-500 mt-1">Tu calendario de actividades y próximos eventos</p>
                </div>

                {{-- Date Display --}}
                <div class="mt-4 md:mt-0 bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-200 flex items-center">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3">
                        <i class="fa fa-calendar-alt"></i>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 uppercase font-bold tracking-wider">Hoy es</span>
                        <span
                            class="block text-gray-800 font-bold">{{ now()->locale('es')->isoFormat('dddd D [de] MMMM') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto space-y-12">

            {{-- SECCIÓN 1: EN CURSO (Lo que está pasando ahora) --}}
            @if($activeCourses->isNotEmpty() || $activeEvents->isNotEmpty())
                <section>
                    <div class="flex items-center mb-6">
                        <span
                            class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mr-3 ring-4 ring-white shadow-sm">
                            <i class="fa fa-clock text-lg"></i>
                        </span>
                        <h2 class="text-xl font-bold text-gray-800">En Curso Ahora</h2>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        {{-- Cursos Activos --}}
                        @foreach($activeCourses as $course)
                            <div
                                class="bg-white rounded-xl p-5 border border-emerald-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                                <div
                                    class="absolute top-0 right-0 w-20 h-20 bg-emerald-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                                </div>

                                <div class="flex items-start relative z-10">
                                    <div class="flex-shrink-0 mr-4">
                                        <div
                                            class="w-14 h-14 bg-emerald-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                                            <span
                                                class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->shortMonthName }}</span>
                                            <span
                                                class="text-xl font-bold leading-none">{{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->day }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-emerald-100 text-emerald-700 mb-1">
                                            Curso
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $course->cur_nombre }}</h3>
                                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course->cur_descripcion }}</p>

                                        <div class="flex items-center text-xs text-gray-500 space-x-4">
                                            <span><i class="fa fa-hourglass-half mr-1 text-emerald-500"></i>
                                                {{ $course->cur_horas }} Horas</span>
                                            @php $prog = $course->programas->first(); @endphp
                                            @if($prog && $prog->pro_horario)
                                                <span><i class="fa fa-clock mr-1 text-emerald-500"></i> {{ $prog->pro_horario }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                    <a href="{{ route('participant.my_courses') }}"
                                        class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Ir al Curso &rarr;</a>
                                </div>
                            </div>
                        @endforeach

                        {{-- Eventos Activos --}}
                        @foreach($activeEvents as $event)
                            <div
                                class="bg-white rounded-xl p-5 border border-purple-100 shadow-sm relative overflow-hidden group hover:shadow-md transition-all">
                                <div
                                    class="absolute top-0 right-0 w-20 h-20 bg-purple-50 rounded-bl-full -mr-4 -mt-4 transition-transform group-hover:scale-110">
                                </div>

                                <div class="flex items-start relative z-10">
                                    <div class="flex-shrink-0 mr-4">
                                        <div
                                            class="w-14 h-14 bg-purple-600 rounded-2xl flex flex-col items-center justify-center text-white shadow-lg shadow-purple-500/30">
                                            <span
                                                class="text-xs font-bold uppercase">{{ \Carbon\Carbon::parse($event->eve_inicia)->shortMonthName }}</span>
                                            <span
                                                class="text-xl font-bold leading-none">{{ \Carbon\Carbon::parse($event->eve_inicia)->day }}</span>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <span
                                            class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-purple-100 text-purple-700 mb-1">
                                            Evento
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-800 mb-1">{{ $event->eve_nombre }}</h3>
                                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $event->eve_descripcion }}</p>

                                        <div class="flex items-center text-xs text-gray-500">
                                            <span><i class="fa fa-calendar-alt mr-1 text-purple-500"></i> Hasta
                                                {{ \Carbon\Carbon::parse($event->eve_finaliza)->format('d M') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                    <a href="{{ route('participant.my_courses') }}"
                                        class="text-sm font-semibold text-purple-600 hover:text-purple-700">Ver Detalles &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- SECCIÓN 2: PRÓXIMOS (Agenda) --}}
            <section>
                <div class="flex items-center mb-6">
                    <span
                        class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-3 ring-4 ring-white shadow-sm">
                        <i class="fa fa-calendar-plus text-lg"></i>
                    </span>
                    <h2 class="text-xl font-bold text-gray-800">Próximas Actividades</h2>
                </div>

                @if($upcomingEvents->isEmpty() && $upcomingAvailableCourses->isEmpty() && $upcomingEnrolledCourses->isEmpty())
                    <div class="bg-white rounded-xl p-8 text-center border border-gray-100 shadow-sm">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-4">
                            <i class="fa fa-calendar-day text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Sin actividades próximas</h3>
                        <p class="text-gray-500 mt-1">No hay eventos o cursos programados próximamente.</p>
                    </div>
                @else
                    <div class="relative pl-8 border-l-2 border-gray-200 space-y-8">

                        {{-- Loop combinando por fecha sería ideal, aqui lo haremos simple por bloques --}}

                        {{-- EVENTOS PROXIMOS --}}
                        @foreach($upcomingEvents as $evento)
                            <div
                                class="relative bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-blue-300 transition-colors">
                                <div
                                    class="absolute -left-[41px] top-6 w-5 h-5 bg-blue-500 rounded-full border-4 border-white shadow-sm">
                                </div>

                                <div class="flex flex-col md:flex-row md:items-start justify-between">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700">Próximo
                                                Evento</span>
                                            <span class="text-sm font-semibold text-gray-500"><i class="fa fa-calendar mr-1"></i>
                                                {{ \Carbon\Carbon::parse($evento->eve_inicia)->format('d F, Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $evento->eve_nombre }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 max-w-2xl">{{ $evento->eve_descripcion }}</p>
                                    </div>

                                    <div class="flex-shrink-0 mt-4 md:mt-0">
                                        @if($evento->eve_tipo)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                {{ $evento->eve_tipo }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- CURSOS PROXIMOS INSCRITOS (Agregado) --}}
                        @foreach($upcomingEnrolledCourses as $course)
                            <div
                                class="relative bg-white p-6 rounded-xl shadow-sm border border-emerald-100 hover:border-emerald-300 transition-colors">
                                <div
                                    class="absolute -left-[41px] top-6 w-5 h-5 bg-emerald-500 rounded-full border-4 border-white shadow-sm">
                                </div>

                                <div class="flex flex-col md:flex-row md:items-start justify-between">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700">Inscrito
                                                - Próximamente</span>
                                            <span class="text-sm font-semibold text-gray-500"><i class="fa fa-play mr-1"></i>
                                                Inicia:
                                                {{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d F, Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $course->cur_nombre }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 max-w-2xl">{{ $course->cur_descripcion }}</p>

                                        @if($course->programas->isNotEmpty())
                                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                                @foreach($course->programas as $prog)
                                                    <span class="flex items-center bg-gray-50 px-2 py-1 rounded">
                                                        <i class="fa fa-clock mr-1 text-emerald-400"></i>
                                                        {{ $prog->pro_horario ?? 'Horario por definir' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0 mt-4 md:mt-0">
                                        <a href="{{ route('participant.my_courses') }}"
                                            class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-emerald-200">
                                            Ver en Mis Cursos
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- CURSOS PROXIMOS DISPONIBLES --}}
                        @foreach($upcomingAvailableCourses as $course)
                            <div
                                class="relative bg-white p-6 rounded-xl shadow-sm border border-gray-100 hover:border-indigo-300 transition-colors">
                                <div
                                    class="absolute -left-[41px] top-6 w-5 h-5 bg-indigo-500 rounded-full border-4 border-white shadow-sm">
                                </div>

                                <div class="flex flex-col md:flex-row md:items-start justify-between">
                                    <div>
                                        <div class="flex items-center space-x-2 mb-2">
                                            <span
                                                class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-indigo-100 text-indigo-700">Disponible
                                                para Inscripción</span>
                                            <span class="text-sm font-semibold text-gray-500"><i class="fa fa-play mr-1"></i>
                                                Inicia:
                                                {{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d F, Y') }}</span>
                                        </div>
                                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $course->cur_nombre }}</h3>
                                        <p class="text-gray-600 text-sm mb-4 max-w-2xl">{{ $course->cur_descripcion }}</p>

                                        @if($course->programas->isNotEmpty())
                                            <div class="flex flex-wrap gap-4 text-xs text-gray-500">
                                                @foreach($course->programas as $prog)
                                                    <span class="flex items-center bg-gray-50 px-2 py-1 rounded">
                                                        <i class="fa fa-clock mr-1 text-indigo-400"></i>
                                                        {{ $prog->pro_horario ?? 'Horario por definir' }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-shrink-0 mt-4 md:mt-0">
                                        <a href="{{ route('participant.dashboard') }}"
                                            class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm shadow-indigo-200">
                                            Ver e Inscribirse
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif
            </section>

        </div>
    </div>
@endsection