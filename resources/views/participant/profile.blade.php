@extends('layouts.participant')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header Perfil -->
            <div class="relative bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8 group">
                <div class="h-32 bg-slate-900 relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900 to-slate-800"></div>
                    <!-- Decoración -->
                    <div
                        class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 bg-blue-500 opacity-10 rounded-full blur-3xl group-hover:opacity-20 transition-opacity duration-700">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 bg-indigo-500 opacity-10 rounded-full blur-3xl">
                    </div>
                </div>
                <div class="px-8 pb-8 flex flex-col md:flex-row items-center md:items-end -mt-12 relative z-10">
                    <div class="p-1.5 bg-white rounded-2xl shadow-lg ring-1 ring-slate-100">
                        <div
                            class="w-24 h-24 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center text-white text-3xl font-bold shadow-inner">
                            {{ strtoupper(substr($user->par_nombre, 0, 1)) }}
                        </div>
                    </div>
                    <div class="mt-4 md:mt-0 md:ml-6 text-center md:text-left flex-1">
                        <h1 class="text-2xl font-bold text-slate-800 mb-1">{{ $user->par_nombre }} {{ $user->par_apellido }}
                        </h1>
                        <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                            <span class="inline-flex items-center text-slate-500 text-sm font-medium">
                                <i class="fa fa-envelope mr-2 text-blue-400"></i> {{ $user->par_correo }}
                            </span>
                            @if($user->par_facultad)
                                <span class="inline-flex items-center text-slate-500 text-sm font-medium">
                                    <i class="fa fa-building mr-2 text-slate-400"></i> {{ $user->par_facultad }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <div class="flex flex-col items-center md:items-end">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Progreso
                                General</span>
                            <span
                                class="inline-flex items-center px-4 py-1.5 bg-blue-50 text-blue-700 text-sm font-bold rounded-full border border-blue-100 shadow-sm">
                                <i class="fa fa-book-open mr-2"></i> {{ $totalCourses }} Cursos Inscritos
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Columna Izquierda: Info y Seguridad -->
                <div class="space-y-8">
                    <!-- Tarjeta Información -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-4 pb-2 border-b border-slate-100">
                            <i class="fa fa-id-card text-blue-600 mr-2"></i> Información Personal
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase">Facultad</span>
                                <p class="text-slate-700 font-medium">{{ $user->par_facultad ?? 'No registrada' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase">Departamento</span>
                                <p class="text-slate-700 font-medium">{{ $user->par_departamento ?? 'No registrado' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase">Sede</span>
                                <p class="text-slate-700 font-medium">{{ $user->par_sede ?? 'No registrada' }}</p>
                            </div>
                            <div>
                                <span class="text-xs font-semibold text-slate-400 uppercase">Perfil</span>
                                <p class="text-slate-700 font-medium">{{ $user->par_perfil ?? 'Participante' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna Derecha: Estadísticas y Cursos -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Nivel de Habilidades (Skills) -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h3
                            class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100 flex items-center justify-between">
                            <span><i class="fa fa-medal text-yellow-500 mr-2"></i> Mis Habilidades</span>
                            <span class="text-xs font-normal text-slate-400">Basado en cursos completados</span>
                        </h3>

                        @if(count($categoryProgress) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($categoryProgress as $stat)
                                    <div
                                        class="relative bg-slate-50 rounded-xl p-4 border border-slate-100 hover:border-{{ $stat['color'] }}-200 transition-colors group">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 rounded-lg bg-{{ $stat['color'] }}-100 text-{{ $stat['color'] }}-600 flex items-center justify-center text-lg mr-3 shadow-sm">
                                                    <i class="fa {{ $stat['icon'] }}"></i>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-700 text-sm">{{ $stat['name'] }}</h4>
                                                    <div
                                                        class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider mt-0.5">
                                                        {{ $stat['level'] }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-2xl font-bold text-slate-800">{{ $stat['count'] }}</span>
                                                <span class="text-[10px] block text-slate-400">Cursos</span>
                                            </div>
                                        </div>

                                        <!-- Progress micro-bar -->
                                        <div class="w-full bg-slate-200 rounded-full h-1.5 mt-2 overflow-hidden">
                                            <div class="bg-{{ $stat['color'] }}-500 h-1.5 rounded-full"
                                                style="width: {{ min(100, $stat['count'] * 10) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8 text-slate-400">
                                <i class="fa fa-chart-simple text-4xl mb-2 opacity-50"></i>
                                <p>Completa cursos para desbloquear tus insignias de habilidad.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Historial Académico (Timeline) -->
                    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 pb-2 border-b border-slate-100">
                            <i class="fa fa-history text-blue-500 mr-2"></i> Historial Académico
                        </h3>

                        @if($finishedCourses->count() > 0)
                            <div class="relative pl-4 border-l-2 border-slate-100 space-y-8 ml-2">
                                @foreach($finishedCourses as $course)
                                    <div class="relative group">
                                        <!-- Dot -->
                                        <div
                                            class="absolute -left-[21px] top-1 w-3 h-3 bg-emerald-500 rounded-full border-2 border-white shadow-sm group-hover:scale-125 transition-transform">
                                        </div>

                                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <h4
                                                    class="font-bold text-slate-800 text-sm group-hover:text-emerald-700 transition-colors">
                                                    {{ $course->cur_nombre }}
                                                </h4>
                                                <p class="text-xs text-slate-500 mt-1">
                                                    {{ $course->categoria ? $course->categoria->cat_nombre : 'General' }} &bull;
                                                    {{ $course->cur_hours ?? 0 }} Horas
                                                </p>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <span
                                                    class="inline-block px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded border border-emerald-100">
                                                    {{ $course->cur_fecha_termino ? \Carbon\Carbon::parse($course->cur_fecha_termino)->format('M Y') : 'Finalizado' }}
                                                </span>

                                                @if($course->is_approved)
                                                    <a href="{{ route('participant.certificates.download', $course->cur_id) }}"
                                                        class="block mt-1 text-xs text-blue-600 hover:text-blue-800 hover:underline font-bold"
                                                        target="_blank">
                                                        <i class="fa fa-download mr-1"></i> Certificado
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-6 text-slate-500">
                                <p>No tienes cursos finalizados en tu historial.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection