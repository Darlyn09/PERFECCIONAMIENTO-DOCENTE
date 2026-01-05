@extends('layouts.participant')

@section('content')
    <div x-data="{
                showEnrollModal: false,
                showEventModal: false,
                selectedCourseId: '',
                selectedCourseName: '',
                selectedCoursePrograms: [],
                selectedProgramId: null,
                selectedEvent: {
                    name: '',
                    description: '',
                    start: '',
                    end: '',
                    type: ''
                },
                formatDate(dateStr) {
                    if (!dateStr) return 'Fecha por definir';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' });
                }
            }">

    <div class="space-y-6">
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa fa-check-circle text-emerald-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-emerald-700 font-bold">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa fa-info-circle text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 font-bold">
                                {{ session('info') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Hero Section (Admin Style) --}}
            <div
                class="relative mb-6 sm:mb-8 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-xl sm:rounded-2xl overflow-hidden shadow-2xl">
                {{-- Patrón de fondo --}}
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-full h-full"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
                    </div>
                </div>

                {{-- Decoración geométrica --}}
                <div class="hidden sm:block absolute right-0 top-0 w-64 h-64 transform translate-x-20 -translate-y-20">
                    <div class="w-full h-full bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-full blur-3xl">
                    </div>
                </div>

                {{-- Contenido Hero --}}
                <div class="relative px-4 sm:px-8 py-6 sm:py-10">
                    <div class="flex flex-col gap-4 sm:gap-6">
                        <div>
                            <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
                                <div
                                    class="w-10 sm:w-14 h-10 sm:h-14 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-lg sm:rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                    <i class="fa fa-user-graduate text-lg sm:text-2xl text-white"></i>
                                </div>
                                <div>
                                    <h1 class="text-xl sm:text-3xl md:text-4xl font-bold text-white tracking-tight">
                                        ¡Hola, {{ explode(' ', Auth::guard('participant')->user()->par_nombre)[0] }}! <span
                                            class="animate-wave inline-block">👋</span>
                                    </h1>
                                    <p class="text-blue-200 text-xs sm:text-sm font-medium tracking-wide uppercase mt-0.5">
                                        Portal del Estudiante
                                    </p>
                                </div>
                            </div>

                            <p class="hidden sm:block text-slate-300 max-w-lg leading-relaxed text-sm sm:text-base">
                                Bienvenido a tu panel de gestión académica. Revisa tus cursos, descarga certificados e
                                inscríbete en nuevos eventos.
                            </p>
                        </div>

                        {{-- Stats Grid (Admin Style - Glass) --}}
                        <div class="hidden sm:flex justify-end">
                            <div
                                class="flex items-center gap-4 sm:gap-6 bg-white/10 backdrop-blur-sm rounded-xl px-4 sm:px-6 py-3 sm:py-4 border border-white/10">
                                <div class="text-center">
                                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $stats['total_courses'] }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Inscritos</p>
                                </div>
                                <div class="w-px h-8 sm:h-10 bg-white/20"></div>
                                <div class="text-center">
                                    <p class="text-xl sm:text-2xl font-bold text-emerald-400">
                                        {{ $stats['approved_courses'] }}</p>
                                    <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Aprobados</p>
                                </div>
                                <div class="w-px h-8 sm:h-10 bg-white/20"></div>
                                <div class="text-center">
                                    <p class="text-xl sm:text-2xl font-bold text-amber-400">{{ $stats['total_hours'] }}h</p>
                                    <p class="text-[10px] sm:text-xs text-blue-200 uppercase tracking-wide">Horas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
                {{-- Columna Principal: Cursos Disponibles --}}
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg">
                        <div
                            class="px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                            <h2 class="font-semibold text-slate-800 flex items-center text-sm sm:text-base">
                                <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 sm:mr-3"></span>
                                Cursos Disponibles
                            </h2>
                            @if($availableCourses->count() > 0)
                                <span class="text-xs sm:text-sm text-blue-600 font-medium">
                                    {{ $availableCourses->count() }} Nuevos
                                </span>
                            @endif
                        </div>

                        <div class="p-4 sm:p-6">
                            @if($availableCourses->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach($availableCourses as $course)
                                        <div
                                            class="bg-white rounded-xl border border-slate-100 hover:border-blue-300 hover:shadow-md transition-all group flex flex-col overflow-hidden">
                                            <div class="h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                                            <div class="p-4 flex flex-col flex-1">
                                                <div class="flex items-start justify-between mb-2">
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-700 uppercase tracking-wide">
                                                        {{ $course->categoria ? $course->categoria->cat_nombre : 'General' }}
                                                    </span>
                                                    <span class="text-xs font-semibold text-slate-400">
                                                        <i class="far fa-clock mr-1"></i> {{ $course->cur_horas }}h
                                                    </span>
                                                </div>

                                                <h3 class="font-bold text-slate-800 text-sm mb-2 line-clamp-2">
                                                    {{ $course->cur_nombre }}</h3>

                                                <div
                                                    class="mt-auto pt-3 border-t border-slate-50 flex items-center justify-between">
                                                    <span class="text-xs text-slate-500">
                                                        <i class="far fa-calendar-alt mr-1"></i>
                                                        {{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d M') }}
                                                    </span>

                                                    @if(in_array($course->cur_id, $enrolledCourseIds))
                                                        <span class="text-xs font-bold text-emerald-600 flex items-center">
                                                            <i class="fa fa-check mr-1"></i> Inscrito
                                                        </span>
                                                    @else
                                                        <button @click="
                                                                                selectedCourseId = '{{ $course->cur_id }}';
                                                                                selectedCourseName = '{{ $course->cur_nombre }}';
                                                                                selectedCoursePrograms = {{ json_encode($course->programas) }};
                                                                                selectedProgramId = null;
                                                                                showEnrollModal = true;
                                                                            "
                                                            class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                                            Inscribirme
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 text-slate-500">
                                    <i class="fa fa-inbox text-3xl mb-2 opacity-30"></i>
                                    <p class="text-sm">No hay cursos disponibles para inscripción en este momento.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Columna Lateral --}}
                <div class="space-y-4 sm:space-y-6">

                    {{-- Último Aprobado Widget --}}
                    @if(isset($lastApprovedCourse) && $lastApprovedCourse->curso)
                        <div
                            class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg text-white p-5 relative overflow-hidden group">
                            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-white opacity-10 rounded-full blur-2xl">
                            </div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-100"><i
                                            class="fa fa-medal mr-1"></i> Logro Reciente</span>
                                </div>
                                <h3 class="font-bold text-white text-sm leading-tight mb-3 line-clamp-2">
                                    {{ $lastApprovedCourse->curso->cur_nombre }}
                                </h3>
                                <a href="{{ route('participant.certificates.download', ['login' => Auth::guard('participant')->user()->par_login, 'courseId' => $lastApprovedCourse->curso->cur_id]) }}"
                                    target="_blank"
                                    class="w-full justify-center px-3 py-2 bg-white text-emerald-600 text-xs font-bold rounded-lg shadow-sm hover:bg-emerald-50 transition-colors flex items-center">
                                    <i class="fa fa-download mr-1.5"></i> Descargar Certificado
                                </a>
                            </div>
                        </div>
                    @endif

                    {{-- Eventos Widget --}}
                    <div class="bg-white/80 backdrop-blur-sm rounded-xl border border-white shadow-lg">
                        <div
                            class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-gradient-to-r from-slate-50 to-transparent rounded-t-xl">
                            <h2 class="font-semibold text-slate-800 text-sm">Próximos Eventos</h2>
                        </div>
                        <div class="divide-y divide-slate-100">
                            @forelse($events as $event)
                                <div class="px-4 py-3 hover:bg-slate-50 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 text-center w-10">
                                            <div class="text-[10px] font-bold text-slate-400 uppercase leading-none">
                                                {{ \Carbon\Carbon::parse($event->eve_fecha)->format('M') }}</div>
                                            <div class="text-lg font-bold text-slate-800 leading-none mt-0.5">
                                                {{ \Carbon\Carbon::parse($event->eve_fecha)->format('d') }}</div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p
                                                class="text-sm font-semibold text-slate-800 line-clamp-1 group-hover:text-blue-600">
                                                {{ $event->eve_nombre }}</p>
                                            <button @click="
                                                        selectedEvent = {
                                                            name: '{{ $event->eve_nombre }}',
                                                            description: `{{ $event->eve_descripcion ?? 'Sin descripción disponible.' }}`,
                                                            start: '{{ $event->eve_inicia }}',
                                                            end: '{{ $event->eve_finaliza }}',
                                                            type: '{{ $event->eve_tipo }}'
                                                        };
                                                        showEventModal = true;
                                                    " class="text-xs text-blue-500 font-medium hover:text-blue-700 mt-1">
                                                Ver detalles
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-slate-500 text-xs text-slate-400">
                                    No hay eventos próximos.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Contacto Rápido --}}
                    <div class="bg-gradient-to-br from-slate-100 to-slate-50 rounded-xl p-4 border border-slate-200">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 bg-slate-200 rounded-lg flex items-center justify-center">
                                <i class="fa fa-headset text-slate-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-slate-700 text-sm">¿Ayuda?</h3>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 mb-3">Si tienes problemas con la plataforma.</p>
                        <a href="{{ route('participant.contact') }}"
                            class="text-xs text-blue-600 hover:text-blue-800 font-bold flex items-center">
                            Ir al Centro de Ayuda <i class="fa fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                </div>
            </div>

        </div>

        <!-- Modal de Confirmación de Inscripción (Alpine.js) -->
        <div x-show="showEnrollModal" @keydown.escape.window="showEnrollModal = false" class="relative z-50"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

            <div x-show="showEnrollModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/75 transition-opacity backdrop-blur-sm"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showEnrollModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.away="showEnrollModal = false"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border-t-4 border-yellow-400">

                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-blue-50 sm:mx-0 sm:h-12 sm:w-12">
                                    <i class="fa fa-graduation-cap text-blue-600 text-xl"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-slate-900" id="modal-title">Confirmar
                                        Inscripción</h3>
                                    <div class="mt-2 text-sm text-slate-500">
                                        <p>Te estás inscribiendo en: <strong class="text-slate-800"
                                                x-text="selectedCourseName"></strong></p>

                                        <!-- Selección de Programa/Sesión -->
                                        <div class="mt-4" x-show="selectedCoursePrograms.length > 0">
                                            <p class="font-medium text-slate-700 mb-2">Selecciona una sesión disponible:</p>
                                            <div class="space-y-2 max-h-60 overflow-y-auto pr-1">
                                                <template x-for="program in selectedCoursePrograms" :key="program.pro_id">
                                                    <label
                                                        class="flex items-start p-3 border rounded-lg cursor-pointer hover:bg-slate-50 transition-colors"
                                                        :class="selectedProgramId == program.pro_id ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-slate-200'">
                                                        <input type="radio" name="program_id" :value="program.pro_id"
                                                            x-model="selectedProgramId"
                                                            class="mt-1 mr-3 text-blue-600 focus:ring-blue-500 border-gray-300">
                                                        <div class="flex-1">
                                                            <div class="flex justify-between">
                                                                <span class="font-bold text-slate-800"
                                                                    x-text="'Inicio: ' + formatDate(program.pro_inicia)"></span>
                                                                <span
                                                                    class="text-xs font-semibold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700"
                                                                    x-text="program.pro_cupos + ' cupos'"></span>
                                                            </div>
                                                            <div class="text-xs text-slate-600 mt-1">
                                                                <i class="fa fa-clock mr-1"></i> <span
                                                                    x-text="program.pro_horario || 'Horario por definir'"></span>
                                                            </div>
                                                            <div class="text-xs text-slate-600 mt-0.5"
                                                                x-show="program.pro_lugar">
                                                                <i class="fa fa-map-marker-alt mr-1"></i> <span
                                                                    x-text="program.pro_lugar"></span>
                                                            </div>
                                                        </div>
                                                    </label>
                                                </template>
                                            </div>
                                        </div>
                                        <div x-show="selectedCoursePrograms.length === 0"
                                            class="mt-4 p-3 bg-yellow-50 text-yellow-700 rounded-lg text-sm">
                                            <i class="fa fa-info-circle mr-1"></i> No hay sesiones específicas listadas. Se
                                            te inscribirá en la lista general.
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <form :action="`{{ route('participant.enroll', '0') }}`.replace('/0', '/' + selectedCourseId)"
                                method="POST" class="w-full sm:w-auto">
                                @csrf
                                <input type="hidden" name="program_id" x-model="selectedProgramId">
                                <button type="submit" :disabled="selectedCoursePrograms.length > 0 && !selectedProgramId"
                                    :class="selectedCoursePrograms.length > 0 && !selectedProgramId ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-500'"
                                    class="inline-flex w-full justify-center rounded-lg bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto transition-colors">
                                    Sí, Inscribirme
                                </button>
                            </form>
                            <button type="button" @click="showEnrollModal = false"
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto transition-colors">
                                Cancelar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal de Detalles del Evento -->
        <div x-show="showEventModal" @keydown.escape.window="showEventModal = false" class="relative z-50"
            aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">

            <div x-show="showEventModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/75 transition-opacity backdrop-blur-sm"></div>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div x-show="showEventModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        @click.away="showEventModal = false"
                        class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border-t-4 border-amber-400">

                        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <i class="fa fa-calendar-day text-amber-600"></i>
                                </div>
                                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                    <h3 class="text-lg font-semibold leading-6 text-slate-900" x-text="selectedEvent.name">
                                    </h3>

                                    <div class="mt-3 space-y-3">
                                        <div class="flex items-center text-sm text-slate-600 bg-slate-50 p-2 rounded-lg">
                                            <i class="far fa-clock w-5 text-amber-500"></i>
                                            <span class="font-medium mr-1">Inicio:</span>
                                            <span x-text="formatDate(selectedEvent.start)"></span>
                                        </div>

                                        <div class="flex items-center text-sm text-slate-600 bg-slate-50 p-2 rounded-lg"
                                            x-show="selectedEvent.end">
                                            <i class="far fa-calendar-check w-5 text-amber-500"></i>
                                            <span class="font-medium mr-1">Término:</span>
                                            <span x-text="formatDate(selectedEvent.end)"></span>
                                        </div>

                                        <div class="mt-4">
                                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">
                                                Descripción</h4>
                                            <p class="text-sm text-slate-600 whitespace-pre-line"
                                                x-text="selectedEvent.description"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                            <button type="button" @click="showEventModal = false"
                                class="inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:ml-3 sm:w-auto transition-colors">
                                Cerrar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection