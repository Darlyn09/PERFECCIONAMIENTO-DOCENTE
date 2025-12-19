@extends('layouts.admin')

@section('title', 'Gestión de Relatores')

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
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-xl shadow-amber-500/30">
                            <i class="fa fa-user-tie text-white text-2xl sm:text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-white mb-1">Gestión de Relatores</h1>
                            <p class="text-indigo-200 text-sm">Administración del cuerpo docente e instructores</p>
                        </div>
                    </div>

                    {{-- Botón Nuevo --}}
                    <a href="{{ route('admin.teachers.create') }}"
                        class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg hover:from-blue-700 hover:to-indigo-700 hover:shadow-xl transition-all transform hover:-translate-y-0.5">
                        <i class="fa fa-user-plus mr-2"></i> Nuevo Relator
                    </a>
                </div>
            </div>
        </div>

        {{-- Alertas --}}
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 px-4 py-3 rounded-r-lg flex items-center">
                <i class="fa fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Búsqueda y Filtros --}}
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6 mb-4 sm:mb-6 border border-slate-200">
            <div class="flex items-center mb-4">
                <div class="w-8 sm:w-10 h-8 sm:h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg sm:rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                    <i class="fa fa-search text-white text-sm sm:text-base"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm sm:text-base">Buscar y Filtrar Relatores</h3>
                    <p class="text-xs text-slate-500 hidden sm:block">Encuentra docentes rápidamente</p>
                </div>
            </div>
            <form action="{{ route('admin.teachers.index') }}" method="GET">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    {{-- Búsqueda --}}
                    <div class="sm:col-span-2 lg:col-span-1 relative">
                        <i class="fa fa-user-tie absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input name="search" value="{{ request('search') }}"
                               class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all placeholder-gray-400 shadow-sm"
                               type="text" placeholder="🔍 Buscar nombre, apellido...">
                    </div>
                    
                    {{-- Filtro Tipo (Interno/Externo) --}}
                    <div class="relative">
                        <i class="fa fa-filter absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                        <select name="tipo"
                                class="w-full pl-10 sm:pl-12 pr-4 py-3 sm:py-3.5 bg-white border border-gray-300 rounded-xl text-sm font-medium focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all appearance-none cursor-pointer shadow-sm">
                            <option value="">🏷️ Todos los Tipos</option>
                            <option value="interno" {{ request('tipo') == 'interno' ? 'selected' : '' }}>🏛️ Internos</option>
                            <option value="externo" {{ request('tipo') == 'externo' ? 'selected' : '' }}>🌐 Externos</option>
                        </select>
                    </div>
                    
                    {{-- Botones --}}
                    <div class="flex gap-2 sm:gap-3">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 sm:px-6 py-3 sm:py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all">
                            <i class="fa fa-filter mr-2"></i> Filtrar
                        </button>
                        @if(request()->hasAny(['search', 'tipo']))
                            <a href="{{ route('admin.teachers.index') }}" class="inline-flex items-center justify-center px-4 sm:px-5 py-3 sm:py-3.5 bg-gradient-to-r from-red-500 to-rose-600 text-white text-sm font-bold rounded-xl hover:from-red-600 hover:to-rose-700 shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                                <i class="fa fa-times mr-2"></i> <span class="hidden sm:inline">Limpiar</span>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        
        {{-- Stats rápidas (Totales globales del sistema) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-blue-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $totalRelatores }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Total Relatores</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-emerald-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $totalHabilitados }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Habilitados</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-blue-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $totalInternos }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Internos</p>
            </div>
            <div class="bg-white rounded-xl p-3 sm:p-4 shadow-md border-l-4 border-amber-500">
                <p class="text-xl sm:text-2xl font-bold text-slate-800">{{ $totalExternos }}</p>
                <p class="text-xs sm:text-sm text-slate-500">Externos</p>
            </div>
        </div>

        {{-- Listado de Relatores --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-white shadow-lg overflow-hidden">
            {{-- Header de tabla --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-4 sm:px-6 py-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-white font-semibold flex items-center text-sm sm:text-base">
                        <i class="fa fa-users mr-2 sm:mr-3"></i>
                        Listado de Relatores
                    </h2>
                    <span class="text-indigo-100 text-xs sm:text-sm">{{ $teachers->total() }} registros</span>
                </div>
            </div>

            {{-- Vista Móvil y Tablet (Tarjetas) --}}
            <div class="block xl:hidden divide-y divide-slate-100">
                @forelse($teachers as $teacher)
                    <div class="p-4 hover:bg-slate-50/50 transition-colors">
                        {{-- Header de tarjeta --}}
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center flex-1 min-w-0">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-3 shadow-lg flex-shrink-0">
                                    <span class="text-lg font-bold text-white">
                                        {{ strtoupper(substr($teacher->rel_nombre ?? 'R', 0, 1)) }}{{ strtoupper(substr($teacher->rel_apellido ?? '', 0, 1)) }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.teachers.show', $teacher->rel_login) }}" 
                                       class="font-bold text-slate-800 hover:text-blue-600 transition-colors block truncate">
                                        {{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}
                                    </a>
                                    <p class="text-xs text-slate-400 flex items-center mt-0.5">
                                        <i class="fa fa-id-badge mr-1"></i>
                                        {{ $teacher->rel_login }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1 flex-shrink-0 ml-2">
                                @if($teacher->rel_facultad)
                                    <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                        <i class="fa fa-university mr-1"></i> Interno
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                        <i class="fa fa-external-link-alt mr-1"></i> Externo
                                    </span>
                                @endif
                                @if($teacher->rel_estado == 1)
                                    <span class="inline-flex items-center px-2 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-full">
                                        <i class="fa fa-circle text-[5px] mr-1 animate-pulse"></i> Activo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 bg-red-50 text-red-600 text-xs font-medium rounded-full">
                                        Inhabilitado
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        {{-- Info del relator --}}
                        <div class="grid grid-cols-1 gap-2 mb-4 text-sm">
                            @if($teacher->rel_correo)
                                <div class="flex items-center text-slate-600">
                                    <i class="fa fa-envelope text-blue-400 mr-2 w-4"></i>
                                    <span class="truncate">{{ $teacher->rel_correo }}</span>
                                </div>
                            @endif
                            @if($teacher->rel_fono)
                                <div class="flex items-center text-slate-600">
                                    <i class="fa fa-phone text-emerald-400 mr-2 w-4"></i>
                                    {{ $teacher->rel_fono }}
                                </div>
                            @endif
                            @if($teacher->rel_cargo)
                                <div class="flex items-center text-slate-600">
                                    <i class="fa fa-briefcase text-amber-400 mr-2 w-4"></i>
                                    {{ $teacher->rel_cargo }}
                                </div>
                            @endif
                            @if($teacher->rel_facultad)
                                <div class="flex items-center text-slate-600">
                                    <i class="fa fa-university text-indigo-400 mr-2 w-4"></i>
                                    <span class="truncate">{{ $teacher->rel_facultad }}</span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Botones de acción móvil --}}
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.teachers.show', $teacher->rel_login) }}" 
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Ver Perfil
                            </a>
                            <button type="button"
                               @click="$dispatch('confirm-action', {
                                   title: '¿Editar este relator?',
                                   message: 'Serás redirigido al formulario de edición.',
                                   itemName: '{{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}',
                                   type: 'edit',
                                   redirectUrl: '{{ route('admin.teachers.edit', $teacher->rel_login) }}'
                               })"
                               class="flex-1 inline-flex items-center justify-center px-3 py-2.5 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-sm font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Editar
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa fa-user-tie text-2xl text-blue-500"></i>
                        </div>
                        <p class="text-slate-600 font-medium mb-1">No hay relatores registrados</p>
                        <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center px-4 py-2 mt-3 bg-blue-600 text-white text-sm font-medium rounded-lg">
                            <i class="fa fa-user-plus mr-2"></i> Registrar Relator
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Vista Desktop Grande (Tabla) --}}
            <div class="hidden xl:block">
                <table class="w-full">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200">
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Relator</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Contacto</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Cargo / Facultad</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($teachers as $teacher)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center mr-4 shadow-lg">
                                            <span class="text-lg font-bold text-white">
                                                {{ strtoupper(substr($teacher->rel_nombre ?? 'R', 0, 1)) }}{{ strtoupper(substr($teacher->rel_apellido ?? '', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.teachers.show', $teacher->rel_login) }}" class="font-semibold text-slate-800 hover:text-blue-600 transition-colors">
                                                {{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}
                                            </a>
                                            <p class="text-xs text-slate-400 flex items-center mt-0.5">
                                                <i class="fa fa-id-badge mr-1.5"></i>
                                                {{ $teacher->rel_login }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if($teacher->rel_correo)
                                            <p class="text-sm text-slate-600 flex items-center">
                                                <i class="fa fa-envelope text-slate-400 w-4 mr-2"></i>
                                                {{ $teacher->rel_correo }}
                                            </p>
                                        @endif
                                        @if($teacher->rel_fono)
                                            <p class="text-sm text-slate-600 flex items-center">
                                                <i class="fa fa-phone text-slate-400 w-4 mr-2"></i>
                                                {{ $teacher->rel_fono }}
                                            </p>
                                        @endif
                                        @if(!$teacher->rel_correo && !$teacher->rel_fono)
                                            <span class="text-slate-400 text-sm">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($teacher->rel_facultad)
                                        <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                                            <i class="fa fa-university mr-1.5"></i>
                                            Interno
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 text-xs font-semibold rounded-full">
                                            <i class="fa fa-external-link-alt mr-1.5"></i>
                                            Externo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        @if($teacher->rel_cargo)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-medium rounded-lg border border-amber-200">
                                                <i class="fa fa-briefcase text-amber-400 mr-1.5"></i>
                                                {{ Str::limit($teacher->rel_cargo, 20) }}
                                            </span>
                                        @endif
                                        @if($teacher->rel_facultad)
                                            <span class="inline-flex items-center px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200">
                                                <i class="fa fa-university text-indigo-400 mr-1.5"></i>
                                                {{ Str::limit($teacher->rel_facultad, 20) }}
                                            </span>
                                        @endif
                                        @if(!$teacher->rel_cargo && !$teacher->rel_facultad)
                                            <span class="text-slate-400 text-sm">—</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($teacher->rel_estado == 1)
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                            Habilitado
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-red-600 bg-red-50 border border-red-200 rounded-full">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5"></span>
                                            Inhabilitado
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Botón Ver Perfil --}}
                                        <a href="{{ route('admin.teachers.show', $teacher->rel_login) }}" 
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-700 text-white rounded-xl hover:from-blue-600 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                           title="Ver perfil">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </a>
                                        {{-- Botón Editar --}}
                                        <button type="button"
                                           @click="$dispatch('confirm-action', {
                                               title: '¿Editar este relator?',
                                               message: 'Serás redirigido al formulario de edición.',
                                               itemName: '{{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}',
                                               type: 'edit',
                                               redirectUrl: '{{ route('admin.teachers.edit', $teacher->rel_login) }}'
                                           })"
                                           class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-amber-400 to-amber-600 text-white rounded-xl hover:from-amber-500 hover:to-amber-700 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                           title="Editar">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        {{-- Botón Toggle Estado --}}
                                        <form id="toggle-teacher-{{ $teacher->rel_login }}" action="{{ route('admin.teachers.toggle', $teacher->rel_login) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            @if($teacher->rel_estado == 1)
                                                <button type="button"
                                                        @click="$dispatch('confirm-action', {
                                                            title: '¿Inhabilitar este relator?',
                                                            message: 'El relator no podrá ser asignado a nuevos cursos mientras esté inhabilitado.',
                                                            itemName: '{{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}',
                                                            type: 'disable',
                                                            formId: 'toggle-teacher-{{ $teacher->rel_login }}'
                                                        })"
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-red-500 to-red-700 text-white rounded-xl hover:from-red-600 hover:to-red-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                        title="Inhabilitar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                </button>
                                            @else
                                                <button type="button"
                                                        @click="$dispatch('confirm-action', {
                                                            title: '¿Habilitar este relator?',
                                                            message: 'El relator podrá ser asignado a cursos nuevamente.',
                                                            itemName: '{{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}',
                                                            type: 'enable',
                                                            formId: 'toggle-teacher-{{ $teacher->rel_login }}'
                                                        })"
                                                        class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-700 text-white rounded-xl hover:from-emerald-600 hover:to-emerald-800 transition-all shadow-lg hover:shadow-xl hover:scale-105"
                                                        title="Habilitar">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mb-4">
                                            <i class="fa fa-user-tie text-3xl text-slate-400"></i>
                                        </div>
                                        <h3 class="text-lg font-semibold text-slate-700 mb-1">No hay relatores registrados</h3>
                                        <p class="text-slate-500 text-sm mb-4">Comienza registrando a los docentes e instructores</p>
                                        <a href="{{ route('admin.teachers.create') }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                            <i class="fa fa-user-plus mr-2"></i> Registrar relator
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($teachers->hasPages())
                {{-- Paginación --}}
                <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white rounded-b-2xl">
                    {{ $teachers->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
