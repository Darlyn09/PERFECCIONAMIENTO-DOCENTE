@extends('layouts.admin')

@section('title', 'Gestión de Cursos y Talleres')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-6">
        {{-- Header --}}
        <div class="mb-4 sm:mb-6">
            {{-- Botón Volver Destacado --}}
            <a href="{{ route('admin.dashboard') }}" 
               class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-4 sm:mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm sm:text-base font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
                <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                <span class="hidden sm:inline">Volver al Dashboard</span>
                <span class="sm:hidden">Volver</span>
            </a>
            
        {{-- Header Principal --}}
        <div class="relative mb-6 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl">
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 opacity-20">
                <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
            </div>

            <div class="relative px-6 sm:px-8 py-6 sm:py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    {{-- Info --}}
                    <div class="flex items-center gap-5">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl shadow-indigo-500/30">
                            <i class="fa fa-graduation-cap text-white text-2xl sm:text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Cursos y Talleres</h1>
                            <p class="text-blue-200 text-sm">Gestión de programas de capacitación profesional</p>
                        </div>
                    </div>

                    {{-- Botón Nuevo --}}
                    <a href="{{ route('admin.courses.create') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa fa-plus mr-2"></i> Nuevo Curso
                    </a>
                </div>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-5 bg-emerald-500 text-white px-5 py-3 rounded-lg flex items-center shadow-lg">
                <i class="fa fa-check-circle mr-3 text-lg"></i>
                <span class="font-medium">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Filtros con diseño llamativo --}}
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 border border-blue-100">
            <div class="flex items-center mb-4">
                <div class="w-8 sm:w-10 h-8 sm:h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                    <i class="fa fa-search text-white text-sm sm:text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Buscar y Filtrar</h3>
                    <p class="text-xs text-slate-500 hidden sm:block">Encuentra cursos rápidamente</p>
                </div>
            </div>
            <form action="{{ route('admin.courses.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    {{-- Búsqueda destacada --}}
                    <div class="sm:col-span-2 lg:col-span-1 relative">
                        <i class="fa fa-search absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-blue-500"></i>
                        <input name="search" value="{{ request('search') }}"
                               class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder-gray-400 shadow-sm"
                               type="text" placeholder="🔍 Buscar curso...">
                    </div>
                    
                    {{-- Fecha --}}
                    <div class="relative">
                        <i class="fa fa-calendar absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-amber-500"></i>
                        <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}"
                               class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all shadow-sm">
                    </div>
                    
                    {{-- Categoría --}}
                    <div class="relative">
                        <i class="fa fa-folder absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-amber-500 z-10"></i>
                        <select name="categoria"
                                class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="">Categorías</option>
                            @foreach($categorias as $cat)
                                <option value="{{ $cat->cur_categoria }}" {{ request('categoria') == $cat->cur_categoria ? 'selected' : '' }}>
                                    {{ $cat->nom_categoria }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Estado --}}
                    <div class="relative">
                        <i class="fa fa-toggle-on absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-emerald-500 z-10"></i>
                        <select name="estado"
                                class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="">Estado</option>
                            <option value="1" {{ request('estado') === '1' ? 'selected' : '' }}>✅ Activos</option>
                            <option value="0" {{ request('estado') === '0' ? 'selected' : '' }}>⏹️ Terminados</option>
                        </select>
                    </div>

                    {{-- Ordenamiento (Nuevo) --}}
                    <div class="relative">
                        <i class="fa fa-sort absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-blue-500 z-10"></i>
                        <select name="orden" onchange="this.form.submit()"
                                class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="fecha_desc" {{ request('orden') == 'fecha_desc' ? 'selected' : '' }}>📅 Fecha (Reciente)</option>
                            <option value="fecha_asc" {{ request('orden') == 'fecha_asc' ? 'selected' : '' }}>📅 Fecha (Antigua)</option>
                            <option value="nombre_asc" {{ request('orden') == 'nombre_asc' ? 'selected' : '' }}>🔤 Nombre (A-Z)</option>
                            <option value="nombre_desc" {{ request('orden') == 'nombre_desc' ? 'selected' : '' }}>🔤 Nombre (Z-A)</option>
                        </select>
                    </div>
                </div>
                
                {{-- Botones de acción --}}
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-4 sm:mt-5 pt-4 sm:pt-5 border-t border-slate-100">
                    <button type="submit" class="inline-flex items-center justify-center px-5 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                        <i class="fa fa-filter mr-2"></i> Aplicar Filtros
                    </button>
                    @if(request()->hasAny(['search', 'fecha_inicio', 'categoria', 'estado']))
                        <a href="{{ route('admin.courses.index') }}" class="inline-flex items-center justify-center px-5 py-2.5 sm:py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-times mr-2"></i> Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Stats rápidas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-blue-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $courses->total() }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Total Cursos</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-emerald-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $courses->where('cur_estado', 1)->count() }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Activos</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-amber-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $courses->where('cur_estado', 0)->count() }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Terminados</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-sky-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $categorias->count() }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Categorías</p>
            </div>
        </div>

        {{-- Listado de Cursos --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-slate-100">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                <h2 class="text-white font-semibold flex items-center text-sm sm:text-base">
                    <i class="fa fa-graduation-cap mr-2 sm:mr-3"></i>
                    Listado de Cursos
                </h2>
            </div>

            {{-- Vista de Tarjetas para Móvil y Tablet --}}
            <div class="block xl:hidden divide-y divide-slate-100">
                @forelse($courses as $course)
                    <div class="p-4 hover:bg-blue-50/50 transition-colors">
                        {{-- Header de tarjeta --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3 shadow-lg flex-shrink-0">
                                    <i class="fa fa-book-open text-white"></i>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.courses.show', $course->cur_id) }}" 
                                       class="font-bold text-slate-800 hover:text-blue-600 transition-colors block truncate">
                                        {{ $course->cur_nombre }}
                                    </a>
                                    @if($course->cur_modalidad)
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            @switch($course->cur_modalidad)
                                                @case(1) <i class="fa fa-building mr-1"></i>Presencial @break
                                                @case(2) <i class="fa fa-video mr-1"></i>Online @break
                                                @case(3) <i class="fa fa-play mr-1"></i>Asincrónico @break
                                                @case(4) <i class="fa fa-laptop mr-1"></i>Híbrido @break
                                            @endswitch
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if($course->cur_estado == 1)
                                <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                    <i class="fa fa-circle text-[5px] mr-1 animate-pulse"></i> Activo
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 bg-slate-200 text-slate-600 text-xs font-semibold rounded-full flex-shrink-0 ml-2">
                                    Terminado
                                </span>
                            @endif
                        </div>
                        
                        {{-- Info del curso --}}
                        <div class="grid grid-cols-2 gap-3 mb-4 text-sm">
                            <div class="flex items-center text-slate-600">
                                <i class="fa fa-folder text-amber-500 mr-2 w-4"></i>
                                <span class="truncate">{{ $course->categoria->nom_categoria ?? 'Sin categoría' }}</span>
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i class="fa fa-calendar text-amber-400 mr-2 w-4"></i>
                                {{ $course->cur_fecha_inicio ? \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d/m/Y') : '—' }}
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i class="fa fa-users text-indigo-400 mr-2 w-4"></i>
                                <span class="{{ ($course->ofertas_activas_count ?? 0) > 0 ? 'text-emerald-600 font-bold' : '' }}">
                                    {{ $course->ofertas_activas_count ?? 0 }} ofertas
                                </span>
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i class="fa fa-clock text-blue-400 mr-2 w-4"></i>
                                {{ $course->cur_horas ?? 0 }} horas
                            </div>
                            <div class="flex items-center text-slate-600">
                                <i class="fa fa-chart-line text-emerald-400 mr-2 w-4"></i>
                                {{ $course->cur_asistencia ?? 0 }}% asist.
                            </div>
                        </div>
                        
                        {{-- Botones de acción móvil --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.courses.show', $course->cur_id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver
                            </a>
                            <a href="{{ route('admin.users.index', ['curso_id' => $course->cur_id]) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-indigo-500 to-purple-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                                Alumnos
                            </a>
                            <a href="{{ route('admin.courses.edit', $course->cur_id) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </a>
                            @if($course->cur_estado == 1)
                                <form id="terminate-course-mobile-{{ $course->cur_id }}" action="{{ route('admin.courses.terminate', $course->cur_id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="button"
                                            @click="$dispatch('confirm-action', {
                                                title: '¿Terminar este curso?',
                                                message: 'El curso se marcará como terminado.',
                                                itemName: '{{ addslashes($course->cur_nombre) }}',
                                                type: 'terminate',
                                                formId: 'terminate-course-mobile-{{ $course->cur_id }}'
                                            })"
                                            class="w-full inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                        </svg>
                                        Terminar
                                    </button>
                                </form>
                            @endif
                            {{-- Botón Eliminar Móvil --}}
                            <form id="delete-course-mobile-{{ $course->cur_id }}" action="{{ route('admin.courses.destroy', $course->cur_id) }}" method="POST" class="flex-1">
                                @csrf
                                @method('DELETE')
                                <button type="button"
                                        @click="$dispatch('confirm-action', {
                                            title: 'Eliminar Curso',
                                            message: '¿Estás seguro de que deseas eliminar este curso?',
                                            itemName: '{{ addslashes($course->cur_nombre) }}',
                                            type: 'delete',
                                            formId: 'delete-course-mobile-{{ $course->cur_id }}',
                                            confirmText: 'Sí, Eliminar'
                                        })"
                                        class="w-full inline-flex items-center justify-center px-3 py-2.5 bg-rose-100 text-rose-600 text-sm font-semibold rounded-xl hover:bg-rose-200 transition-all shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa fa-graduation-cap text-2xl text-blue-500"></i>
                        </div>
                        <p class="text-slate-600 font-medium mb-1">No hay cursos registrados</p>
                        <a href="{{ route('admin.courses.create') }}" class="inline-flex items-center px-4 py-2 mt-3 bg-blue-600 text-white text-sm font-medium rounded-lg">
                            <i class="fa fa-plus mr-2"></i> Crear Curso
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Tabla para Desktop Grande --}}
            <div class="hidden xl:block">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="text-left px-5 py-4 font-semibold text-slate-600 text-sm">Nombre del Curso</th>
                            <th class="text-left px-5 py-4 font-semibold text-slate-600 text-sm">Categoría</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Fecha</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Duración</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Ofertas Activas</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Asistencia</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Estado</th>
                            <th class="text-center px-5 py-4 font-semibold text-slate-600 text-sm">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($courses as $course)
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-5 py-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center mr-3 shadow">
                                            <i class="fa fa-book-open text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.courses.show', $course->cur_id) }}" 
                                               class="font-semibold text-slate-800 hover:text-blue-600 transition-colors">
                                                {{ Str::limit($course->cur_nombre, 35) }}
                                            </a>
                                            @if($course->cur_modalidad)
                                                <p class="text-xs text-slate-400">
                                                    @switch($course->cur_modalidad)
                                                        @case(1) <i class="fa fa-building mr-1"></i>Presencial @break
                                                        @case(2) <i class="fa fa-video mr-1"></i>Online @break
                                                        @case(3) <i class="fa fa-play mr-1"></i>Asincrónico @break
                                                        @case(4) <i class="fa fa-laptop mr-1"></i>Híbrido @break
                                                    @endswitch
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @if($course->categoria)
                                        <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-full">
                                            {{ $course->categoria->nom_categoria }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($course->cur_fecha_inicio)
                                        <span class="text-sm font-medium text-slate-700">
                                            {{ \Carbon\Carbon::parse($course->cur_fecha_inicio)->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 text-sm font-semibold rounded-lg">
                                        <i class="fa fa-clock mr-1.5 text-blue-400"></i>
                                        {{ $course->cur_horas ?? 0 }}h
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ ($course->ofertas_activas_count ?? 0) > 0 ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $course->ofertas_activas_count ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <div class="flex items-center justify-center">
                                        <div class="w-20 h-2 bg-slate-200 rounded-full overflow-hidden mr-2">
                                            <div class="h-full bg-emerald-500 rounded-full" style="width: {{ min($course->cur_asistencia ?? 0, 100) }}%"></div>
                                        </div>
                                        <span class="text-sm font-semibold text-slate-600">{{ $course->cur_asistencia ?? 0 }}%</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($course->cur_estado == 1)
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            <i class="fa fa-circle text-[6px] mr-1.5 animate-pulse"></i> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-slate-200 text-slate-600 text-xs font-semibold rounded-full">
                                            <i class="fa fa-circle text-[6px] mr-1.5"></i> Terminado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Botón Ver --}}
                                        <a href="{{ route('admin.courses.show', $course->cur_id) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                           title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>

                                        {{-- Botón Ver Participantes --}}
                                        <a href="{{ route('admin.users.index', ['curso_id' => $course->cur_id]) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                           title="Ver Participantes">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </a>

                                        {{-- Botón Editar --}}
                                        <a href="{{ route('admin.courses.edit', $course->cur_id) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-xl hover:from-amber-500 hover:to-amber-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                           title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </a>
                                        @if($course->cur_estado == 1)
                                            {{-- Botón Terminar --}}
                                            <form id="terminate-course-{{ $course->cur_id }}" action="{{ route('admin.courses.terminate', $course->cur_id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="button"
                                                        @click="$dispatch('confirm-action', {
                                                            title: '¿Terminar este curso?',
                                                            message: 'El curso se marcará como terminado y ya no aparecerá como activo.',
                                                            itemName: '{{ addslashes($course->cur_nombre) }}',
                                                            type: 'terminate',
                                                            formId: 'terminate-course-{{ $course->cur_id }}'
                                                        })"
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                        title="Terminar curso">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            {{-- Curso terminado --}}
                                            <span class="inline-flex items-center justify-center w-10 h-10 bg-slate-200 text-slate-400 rounded-xl cursor-not-allowed" title="Curso terminado">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </span>
                                        @endif

                                        {{-- Botón Eliminar (Nuevo) --}}
                                        <form id="delete-course-{{ $course->cur_id }}" action="{{ route('admin.courses.destroy', $course->cur_id) }}" method="POST" class="inline ml-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    @click="$dispatch('confirm-action', {
                                                        title: 'Eliminar Curso',
                                                        message: '¿Estás seguro de que deseas eliminar este curso? Esta acción es irreversible.',
                                                        itemName: '{{ addslashes($course->cur_nombre) }}',
                                                        type: 'delete',
                                                        formId: 'delete-course-{{ $course->cur_id }}',
                                                        confirmText: 'Sí, Eliminar'
                                                    })"
                                                    class="inline-flex items-center justify-center w-10 h-10 bg-rose-100 text-rose-600 rounded-xl hover:bg-rose-200 transition-all shadow-sm hover:shadow-md hover:scale-105"
                                                    title="Eliminar permanentemente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                                            <i class="fa fa-graduation-cap text-2xl text-blue-500"></i>
                                        </div>
                                        <p class="text-slate-600 font-medium mb-1">No hay cursos registrados</p>
                                        <p class="text-slate-400 text-sm mb-4">Comienza creando tu primer curso</p>
                                        <a href="{{ route('admin.courses.create') }}" 
                                           class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fa fa-plus mr-2"></i> Crear Curso
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación responsiva --}}
            @if($courses->hasPages())
                {{-- Paginación --}}
                <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white rounded-b-2xl">
                    {{ $courses->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
