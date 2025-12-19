@extends('layouts.admin')

@section('title', 'Detalle del Curso')

@section('content')
<div class="min-h-screen -m-4 p-4 sm:p-8 relative overflow-hidden bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
    {{-- Fondo dinámico --}}
    <div class="absolute inset-0 opacity-40">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-100 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-indigo-100 rounded-full blur-3xl"></div>
    </div>

    <div class="relative max-w-7xl mx-auto">
        {{-- Navegación --}}
        <a href="{{ route('admin.courses.index') }}" 
           class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-6 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Volver a Cursos
        </a>

        {{-- Header Dark Hero --}}
        <div class="relative mb-8 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-6 sm:p-8">
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 opacity-20">
                <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
            </div>

            <div class="relative flex flex-col md:flex-row gap-6">
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        @if($course->cur_estado == 1)
                            <span class="px-2.5 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-semibold rounded-lg backdrop-blur-sm">
                                Activo
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-red-500/20 text-red-300 border border-red-500/30 text-xs font-semibold rounded-lg backdrop-blur-sm">
                                Terminado
                            </span>
                        @endif
                        
                        @if($course->categoria)
                            <span class="px-2.5 py-0.5 bg-white/10 text-indigo-200 border border-white/10 text-xs font-semibold rounded-lg backdrop-blur-sm">
                                {{ $course->categoria->nom_categoria }}
                            </span>
                        @endif

                        @if($course->evento)
                            <a href="{{ route('admin.events.show', $course->eve_id) }}" class="px-2.5 py-0.5 bg-blue-500/20 text-blue-200 border border-blue-500/30 text-xs font-semibold rounded-lg backdrop-blur-sm hover:bg-blue-500/30 transition-colors flex items-center gap-1">
                                <i class="far fa-calendar-alt text-xs"></i>
                                {{ $course->evento->eve_nombre }}
                            </a>
                        @endif
                    </div>

                    <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3 tracking-tight">{{ $course->cur_nombre }}</h1>

                    @if($course->cur_descripcion)
                        <p class="text-indigo-100 text-sm max-w-3xl leading-relaxed opacity-90 mb-6">
                            {{ $course->cur_descripcion }}
                        </p>
                    @endif

                    {{-- Stats del Header --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 pt-6 border-t border-white/10">
                        <div>
                            <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Duración</p>
                            <p class="text-white font-bold text-lg">
                                <i class="far fa-clock text-amber-400 mr-1.5 text-sm"></i>{{ $course->cur_horas ?? 0 }} hrs
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Inicio</p>
                            <p class="text-white font-bold text-lg">
                                {{ $course->cur_fecha_inicio ? \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d M Y') : '---' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Modalidad</p>
                            <p class="text-white font-bold text-sm">
                                @switch($course->cur_modalidad)
                                    @case(1) Presencial @break
                                    @case(2) Online Sincrónico @break
                                    @case(3) Online Asincrónico @break
                                    @case(4) Híbrido @break
                                    @default Sin definir
                                @endswitch
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-indigo-300 uppercase font-bold mb-1">Sesiones</p>
                            <p class="text-white font-bold text-lg flex items-center">
                                <span class="w-6 h-6 rounded-md bg-white/10 flex items-center justify-center mr-2 text-xs border border-white/10">
                                    {{ $course->programas->count() }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-row md:flex-col gap-3 min-w-[140px]">
                    <a href="{{ route('admin.courses.edit', $course->cur_id) }}"
                        class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-white/5 hover:bg-white/10 text-white font-semibold rounded-xl transition-all border border-white/10 backdrop-blur-sm group">
                        <i class="fas fa-pencil-alt mr-2 text-indigo-300 group-hover:text-white transition-colors"></i>
                        Editar
                    </a>
                    
                    @if($course->cur_estado == 1)
                        <form action="{{ route('admin.courses.terminate', $course->cur_id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <button type="submit" onclick="return confirm('¿Está seguro de terminar este curso?')"
                                    class="w-full h-full inline-flex items-center justify-center px-4 py-3 bg-rose-500/20 hover:bg-rose-500/30 text-rose-200 hover:text-white font-semibold rounded-xl transition-all border border-rose-500/30 backdrop-blur-sm">
                                <i class="far fa-stop-circle mr-2"></i> Terminar
                            </button>
                        </form>
                    @endif

                    <a href="{{ route('admin.programs.create', ['curso' => $course->cur_id]) }}"
                        class="flex-1 inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-bold rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 border border-emerald-400/30">
                        <i class="fas fa-plus mr-2"></i> Nueva Sesión
                    </a>
                </div>
            </div>
        </div>

        {{-- Mensajes --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-xl shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Columna Principal - Programas --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Sección de Programas/Clases --}}
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-slate-800 flex items-center mb-6">
                            <span class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center mr-3 text-amber-600">
                                <i class="fas fa-chalkboard"></i>
                            </span>
                            Programas / Sesiones
                        </h3>

                        @if($course->programas->count() > 0)
                            <div class="space-y-4">
                                @foreach($course->programas as $index => $programa)
                                    <div class="group relative bg-slate-50 rounded-xl p-5 hover:bg-white hover:shadow-md transition-all border border-slate-200">
                                        {{-- Número de sesión --}}
                                        <div class="absolute -left-3 top-6 w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-amber-500/30 z-10">
                                            {{ $index + 1 }}
                                        </div>
                                        
                                        {{-- Botón editar --}}
                                        <a href="{{ route('admin.programs.edit', $programa->pro_id) }}" 
                                           class="absolute top-4 right-4 p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all"
                                           title="Editar programa">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        
                                        <div class="ml-6">
                                            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-3 mb-3">
                                                <div>
                                                    <h4 class="font-bold text-slate-800 mb-1 group-hover:text-blue-700 transition-colors">
                                                        Sesión {{ $index + 1 }}
                                                    </h4>
                                                    @if($programa->pro_horario)
                                                        <p class="text-slate-500 text-sm flex items-start gap-2 max-w-xl">
                                                            <i class="fas fa-info-circle text-slate-400 mt-0.5"></i>
                                                            {{ $programa->pro_horario }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex flex-wrap gap-2 text-xs font-medium">
                                                @if($programa->pro_inicia)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 bg-white rounded-lg text-slate-600 border border-slate-200 shadow-sm">
                                                        <i class="far fa-calendar-alt text-blue-500 mr-1.5"></i>
                                                        {{ \Carbon\Carbon::parse($programa->pro_inicia)->format('d/m/Y') }}
                                                    </span>
                                                @endif
                                                @if($programa->pro_hora_inicio)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 bg-white rounded-lg text-slate-600 border border-slate-200 shadow-sm">
                                                        <i class="far fa-clock text-amber-500 mr-1.5"></i>
                                                        {{ $programa->pro_hora_inicio }}
                                                        @if($programa->pro_hora_termino)
                                                            - {{ $programa->pro_hora_termino }}
                                                        @endif
                                                    </span>
                                                @endif
                                                @if($programa->pro_lugar)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 bg-white rounded-lg text-slate-600 border border-slate-200 shadow-sm">
                                                        <i class="fas fa-map-marker-alt text-red-500 mr-1.5"></i>
                                                        {{ $programa->pro_lugar }}
                                                    </span>
                                                @endif
                                                @if($programa->pro_cupos)
                                                    <span class="inline-flex items-center px-2.5 py-1.5 bg-white rounded-lg text-slate-600 border border-slate-200 shadow-sm">
                                                        <i class="fas fa-users text-emerald-500 mr-1.5"></i>
                                                        {{ $programa->pro_cupos }} cupos
                                                    </span>
                                                @endif
                                            </div>

                                            @if($programa->relator || $programa->rel_login)
                                                <div class="mt-4 pt-4 border-t border-slate-100 flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 shrink-0 text-xs">
                                                        <i class="fas fa-user-tie"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Relator Principal</p>
                                                        <p class="text-sm font-semibold text-slate-700">
                                                            {{ $programa->relator->rel_nombre ?? $programa->rel_login }} 
                                                            {{ $programa->relator->rel_apellido ?? '' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif

                                            @if($programa->pro_colaboradores || $programa->relatores->count() > 0)
                                                <div class="mt-3 flex items-start gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 shrink-0 text-xs">
                                                        <i class="fas fa-users"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Colaboradores</p>
                                                        
                                                        @if($programa->pro_colaboradores)
                                                            <p class="text-xs text-slate-600 mb-1 leading-tight">
                                                                {{ $programa->pro_colaboradores }}
                                                            </p>
                                                        @endif

                                                        @if($programa->relatores->count() > 0)
                                                            <div class="flex flex-col gap-1 mt-1">
                                                                @foreach($programa->relatores as $docente)
                                                                    <span class="text-xs text-slate-700 font-medium flex items-center">
                                                                        <i class="fas fa-check text-[10px] text-blue-500 mr-1.5"></i>
                                                                        {{ $docente->rel_nombre }} {{ $docente->rel_apellido }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Estado vacío --}}
                            <div class="text-center py-12 bg-slate-50 rounded-xl border-2 border-dashed border-slate-300">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 flex items-center justify-center">
                                    <i class="fas fa-chalkboard text-2xl text-slate-400"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-700 mb-2">No hay sesiones registradas</h3>
                                <p class="text-slate-500 text-sm mb-6 max-w-sm mx-auto">Comienza agregando sesiones o clases a este curso para gestionar la asistencia.</p>
                                <a href="{{ route('admin.programs.create', ['curso' => $course->cur_id]) }}" 
                                   class="inline-flex items-center px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg hover:shadow-blue-500/30">
                                    <i class="fas fa-plus mr-2"></i> Agregar Primera Sesión
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Contenido Extra --}}
                @if($course->cur_objetivos || $course->cur_contenidos)
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <div class="p-6 sm:p-8">
                            <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                                <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                                Información Académica
                            </h3>
                            
                            @if($course->cur_objetivos)
                                <div class="mb-6">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Objetivos</h4>
                                    <div class="prose prose-sm prose-slate max-w-none text-slate-600">
                                        {{ $course->cur_objetivos }}
                                    </div>
                                </div>
                            @endif

                            @if($course->cur_contenidos)
                                <div>
                                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Contenidos</h4>
                                    <div class="prose prose-sm prose-slate max-w-none text-slate-600 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                        {{ $course->cur_contenidos }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Columna Lateral --}}
            <div class="space-y-6">
                {{-- Lista de Inscritos (Acceso Rápido) --}}
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide">Participantes</h3>
                            <a href="{{ route('admin.users.index', ['curso_id' => $course->cur_id]) }}" class="text-blue-600 hover:text-blue-700 text-xs font-bold hover:underline">
                                Ver todos
                            </a>
                        </div>
                        
                        <div class="flex items-center justify-between bg-blue-50 rounded-xl p-4 border border-blue-100 mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-blue-600 font-medium uppercase">Inscritos</p>
                                    <p class="text-xl font-bold text-blue-800">
                                        {{-- Esto debería venir del count real si fuera posible, simulamos o usamos lo que haya --}}
                                        Ver lista
                                    </p>
                                </div>
                            </div>
                            <div class="h-8 w-px bg-blue-200"></div>
                            <div class="text-right">
                                <p class="text-xs text-blue-500">Cupos</p>
                                <p class="font-bold text-blue-700">{{ $course->programas->sum('pro_cupos') }}</p>
                            </div>
                        </div>

                        <a href="{{ route('admin.users.create') }}?curso_id={{ $course->cur_id }}" 
                           class="flex w-full items-center justify-center px-4 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition-all shadow-lg hover:shadow-xl">
                            <i class="fas fa-user-plus mr-2"></i> Inscribir Alumno
                        </a>
                    </div>
                </div>

                {{-- Ubicación / Recursos --}}
                <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4">Recursos y Ubicación</h3>
                        
                        @php
                            $firstProg = $course->programas->first();
                            $adminLoc = $firstProg->pro_lugar ?? null;
                            $mapLink = $course->cur_link;
                            if (!$mapLink && $adminLoc) {
                                $mapLink = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($adminLoc);
                            }
                        @endphp

                        <div class="space-y-3">
                            @if($course->cur_link)
                                <a href="{{ $course->cur_link }}" target="_blank" 
                                   class="flex items-center p-3 rounded-xl bg-indigo-50 text-indigo-700 hover:bg-indigo-100 transition-colors border border-indigo-100 group">
                                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-indigo-500 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-link"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold uppercase opacity-70">Enlace Externo</p>
                                        <p class="font-semibold truncate">Abrir recurso</p>
                                    </div>
                                    <i class="fas fa-external-link-alt text-xs opacity-50"></i>
                                </a>
                            @endif

                            @if($adminLoc)
                                <a href="{{ $mapLink }}" target="_blank" 
                                   class="flex items-center p-3 rounded-xl bg-orange-50 text-orange-700 hover:bg-orange-100 transition-colors border border-orange-100 group">
                                    <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center text-orange-500 shadow-sm mr-3 group-hover:scale-110 transition-transform">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold uppercase opacity-70">Ubicación</p>
                                        <p class="font-semibold truncate">{{ $adminLoc }}</p>
                                    </div>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                @if($course->cur_metodologias || $course->cur_bibliografia)
                    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
                        <div class="p-6">
                            @if($course->cur_metodologias)
                                <div class="mb-5 last:mb-0">
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Metodologías</h3>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($course->cur_metodologias, 150) }}</p>
                                </div>
                            @endif

                            @if($course->cur_bibliografia)
                                <div class="mb-0">
                                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Bibliografía</h3>
                                    <p class="text-sm text-slate-600 leading-relaxed">{{ Str::limit($course->cur_bibliografia, 150) }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
