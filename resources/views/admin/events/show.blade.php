@extends('layouts.admin')

@section('title', $event->eve_nombre)

@section('content')
    <div
        class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        {{-- Fondo dinámico (reducido) --}}
        <div class="absolute inset-0 opacity-40">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
        </div>

        <div class="relative max-w-7xl mx-auto">
            {{-- Botón Volver --}}
            <a href="{{ route('admin.events.index') }}"
                class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Volver a Eventos
            </a>

            {{-- Header Dark Hero (Consistente con Index/Dashboard) --}}
            <div
                class="relative mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-6 sm:p-8">
                {{-- Decoración de fondo --}}
                <div class="absolute inset-0 opacity-20">
                    <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                    <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
                </div>

                <div class="relative flex flex-col md:flex-row gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            @if($event->eve_tipo)
                                <span
                                    class="px-2.5 py-0.5 bg-white/10 text-blue-200 text-xs font-semibold rounded-lg backdrop-blur-sm border border-white/10">
                                    {{ match ($event->eve_tipo) { 1 => 'Capacitación', 2 => 'Seminario', 3 => 'Congreso', 4 => 'Taller', 5 => 'Jornada', default => 'Evento'} }}
                                </span>
                            @endif
                            <span
                                class="px-2.5 py-0.5 {{ $event->eve_estado == 1 ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30' : 'bg-slate-500/20 text-slate-300 border-slate-500/30' }} text-xs font-semibold rounded-lg border backdrop-blur-sm">
                                {{ $event->eve_estado == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>

                        <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3 tracking-tight">{{ $event->eve_nombre }}
                        </h1>

                        @if($event->eve_descripcion)
                            <p class="text-indigo-100 text-sm max-w-3xl leading-relaxed opacity-90 mb-6">
                                {{ $event->eve_descripcion }}
                            </p>
                        @endif

                        {{-- Stats del Header --}}
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-6 border-t border-white/10">
                            <div>
                                <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Inicio</p>
                                <p class="text-white font-bold text-lg">
                                    {{ $event->eve_inicia ? \Carbon\Carbon::parse($event->eve_inicia)->format('d M Y') : '---' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Término</p>
                                <p class="text-white font-bold text-lg">
                                    {{ $event->eve_finaliza ? \Carbon\Carbon::parse($event->eve_finaliza)->format('d M Y') : '---' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Inscripciones</p>
                                <p class="text-white font-bold text-sm">
                                    @if($event->eve_abre && $event->eve_cierra)
                                        {{ \Carbon\Carbon::parse($event->eve_abre)->format('d/m') }} -
                                        {{ \Carbon\Carbon::parse($event->eve_cierra)->format('d/m') }}
                                    @else
                                        <span class="opacity-50">No definidas</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Cursos</p>
                                <p class="text-white font-bold text-lg flex items-center">
                                    <i class="fas fa-layer-group text-amber-400 mr-2 text-sm"></i>
                                    {{ $event->cursos->count() }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex flex-row md:flex-col gap-3 min-w-[140px]">
                        <a href="{{ route('admin.events.edit', $event->eve_id) }}"
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-xl transition-all border border-white/10 backdrop-blur-sm group">
                            <i class="fas fa-pencil-alt mr-2 text-indigo-300 group-hover:text-white transition-colors"></i>
                            Editar
                        </a>
                        <a href="{{ route('admin.courses.create') }}?evento={{ $event->eve_id }}"
                            class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 border border-blue-400/30">
                            <i class="fas fa-plus mr-2"></i> Nuevo Curso
                        </a>
                    </div>
                </div>
            </div>

            {{-- Contenido: Cursos --}}
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-800 flex items-center mb-6">
                    <span class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center mr-3 text-blue-600">
                        <i class="fas fa-stream"></i>
                    </span>
                    Cursos Asociados
                </h3>

                @if(session('success'))
                    <div
                        class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-xl shadow-sm flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if($event->cursos->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($event->cursos as $curso)
                            <div
                                class="group bg-white rounded-xl shadow-[0_4px_20px_rgb(0,0,0,0.08)] border border-slate-200 overflow-hidden hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:border-blue-400 transition-all duration-300 hover:-translate-y-1">
                                {{-- Barra estado --}}
                                <div class="h-1.5 {{ $curso->cur_estado == 1 ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>

                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $curso->cur_estado == 1 ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $curso->cur_estado == 1 ? 'Activo' : 'Finalizado' }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-400 flex items-center">
                                            <i class="far fa-clock mr-1"></i> {{ $curso->cur_horas ?? 0 }}h
                                        </span>
                                    </div>

                                    <h4
                                        class="text-lg font-bold text-slate-800 mb-2 line-clamp-2 min-h-[3.5rem] group-hover:text-blue-700 transition-colors">
                                        {{ $curso->cur_nombre }}
                                    </h4>

                                    @if($curso->categoria)
                                        <p class="text-xs font-medium text-slate-500 mb-4 flex items-center">
                                            <i class="far fa-folder text-amber-500 mr-2"></i>
                                            {{ $curso->categoria->nom_categoria }}
                                        </p>
                                    @endif

                                    <div class="space-y-2 mb-5 pt-4 border-t border-slate-100">
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="far fa-calendar-alt text-blue-400 w-5"></i>
                                            <span
                                                class="font-medium">{{ $curso->cur_fecha_inicio ? \Carbon\Carbon::parse($curso->cur_fecha_inicio)->format('d/m/Y') : '--' }}</span>
                                        </div>
                                        <div class="flex items-center text-sm text-slate-600">
                                            <i class="fas fa-globe text-purple-400 w-5"></i>
                                            <span>
                                                {{ match ($curso->cur_modalidad) { 1 => 'Presencial', 2 => 'Online Sincrónico', 3 => 'Online Asincrónico', 4 => 'Híbrido', default => 'No definido'} }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.users.index', ['curso_id' => $curso->cur_id]) }}"
                                            class="flex-1 inline-flex items-center justify-center py-2 px-3 bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-blue-600 font-bold text-xs uppercase tracking-wide rounded-lg transition-colors border border-slate-200 hover:border-blue-200">
                                            <i class="fas fa-users mr-2"></i> Inscritos
                                        </a>
                                        <a href="{{ route('admin.courses.edit', $curso->cur_id) }}"
                                            class="p-2 inline-flex items-center justify-center bg-white border border-slate-200 text-slate-400 hover:text-amber-500 hover:border-amber-400 rounded-lg transition-all shadow-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div
                        class="flex flex-col items-center justify-center py-16 px-4 bg-white border-2 border-dashed border-slate-300 rounded-3xl">
                        <div class="w-16 h-16 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-layer-group text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700 mb-1">Sin cursos asociados</h3>
                        <p class="text-slate-500 text-center max-w-sm mb-6">Este evento aún no tiene cursos o talleres
                            registrados.</p>
                        <a href="{{ route('admin.courses.create') }}?evento={{ $event->eve_id }}"
                            class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30">
                            <i class="fas fa-plus mr-2"></i> Agregar Primer Curso
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection