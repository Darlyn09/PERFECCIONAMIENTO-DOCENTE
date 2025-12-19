@extends('layouts.admin')

@section('title', 'Perfil de Relator')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-4 sm:p-8">
        {{-- Botón Volver --}}
        <a href="{{ route('admin.teachers.index') }}" 
           class="inline-flex items-center px-4 sm:px-5 py-2 sm:py-2.5 mb-5 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm sm:text-base font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            <span class="hidden sm:inline">Volver a Relatores</span>
            <span class="sm:hidden">Volver</span>
        </a>

        {{-- Header del Perfil --}}
        <div class="relative mb-6 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-8">
            {{-- Decoración --}}
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 opacity-20">
                <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
            </div>
            
            <div class="relative px-4 sm:px-8 py-6 sm:py-10">
                <div class="flex flex-col md:flex-row md:items-center gap-6">
                    {{-- Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-white/20 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-amber-400/30">
                            <span class="text-4xl sm:text-5xl font-bold text-white">
                                {{ strtoupper(substr($teacher->rel_nombre ?? 'R', 0, 1)) }}{{ strtoupper(substr($teacher->rel_apellido ?? '', 0, 1)) }}
                            </span>
                        </div>
                    </div>
                    
                    {{-- Info Principal --}}
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            {{-- Badge Tipo --}}
                            @if($teacher->es_interno)
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    Interno
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-amber-500 text-white text-xs font-bold rounded-full shadow-lg">
                                    <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Externo
                                </span>
                            @endif
                            
                            {{-- Badge Estado --}}
                            @if($teacher->rel_estado == 1)
                                <span class="inline-flex items-center px-3 py-1 bg-white/20 text-white text-xs font-semibold rounded-full">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full mr-1.5 animate-pulse"></span>
                                    Habilitado
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-red-500/80 text-white text-xs font-semibold rounded-full">
                                    Inhabilitado
                                </span>
                            @endif
                        </div>
                        
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-1">
                            {{ $teacher->rel_nombre }} {{ $teacher->rel_apellido }}
                        </h1>
                        
                        @if($teacher->rel_cargo)
                            <p class="text-blue-100 text-sm sm:text-base flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $teacher->rel_cargo }}
                            </p>
                        @endif
                        
                        @if($teacher->rel_facultad)
                            <p class="text-blue-200 text-sm mt-1 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                {{ $teacher->rel_facultad }}
                            </p>
                        @endif
                    </div>
                    
                    {{-- Acciones --}}
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('admin.teachers.edit', $teacher->rel_login) }}" 
                           class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Editar
                        </a>
                    </div>
                </div>
                
                {{-- Estadísticas rápidas --}}
                <div class="grid grid-cols-3 gap-3 sm:gap-4 mt-6 sm:mt-8">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center border border-amber-400/20">
                        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalCursos }}</p>
                        <p class="text-xs sm:text-sm text-blue-200">Cursos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center border border-amber-400/20">
                        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalProgramas }}</p>
                        <p class="text-xs sm:text-sm text-blue-200">Sesiones</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 sm:p-4 text-center border border-amber-400/20">
                        <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalHoras }}</p>
                        <p class="text-xs sm:text-sm text-blue-200">Horas</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- Columna Información --}}
            <div class="space-y-4 sm:space-y-6">
                {{-- Información de Contacto --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-slate-800 to-slate-900 px-5 py-4">
                        <h2 class="text-white font-semibold flex items-center">
                            <i class="fa fa-address-card mr-3 text-blue-400"></i>
                            Información de Contacto
                        </h2>
                    </div>
                    <div class="p-5 space-y-4">
                        {{-- Login/ID --}}
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center mr-3">
                                <i class="fa fa-id-badge text-slate-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Login / RUT</p>
                                <p class="font-semibold text-slate-800">{{ $teacher->rel_login }}</p>
                            </div>
                        </div>
                        
                        {{-- Correo --}}
                        @if($teacher->rel_correo)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fa fa-envelope text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Correo Electrónico</p>
                                    <a href="mailto:{{ $teacher->rel_correo }}" class="font-semibold text-blue-600 hover:text-blue-800">
                                        {{ $teacher->rel_correo }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Teléfono --}}
                        @if($teacher->rel_fono)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center mr-3">
                                    <i class="fa fa-phone text-emerald-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Teléfono</p>
                                    <p class="font-semibold text-slate-800">{{ $teacher->rel_fono }}</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Cargo --}}
                        @if($teacher->rel_cargo)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center mr-3">
                                    <i class="fa fa-briefcase text-amber-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Cargo</p>
                                    <p class="font-semibold text-slate-800">{{ $teacher->rel_cargo }}</p>
                                </div>
                            </div>
                        @endif
                        
                        {{-- Facultad --}}
                        @if($teacher->rel_facultad)
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center mr-3">
                                    <i class="fa fa-university text-indigo-600"></i>
                                </div>
                                <div>
                                    <p class="text-xs text-slate-500">Facultad / Unidad</p>
                                    <p class="font-semibold text-slate-800">{{ $teacher->rel_facultad }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Tipo de Relator --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-5">
                        <h3 class="font-semibold text-slate-800 mb-4 flex items-center">
                            <i class="fa fa-user-tag mr-2 text-purple-500"></i>
                            Tipo de Relator
                        </h3>
                        
                        @if($teacher->es_interno)
                            <div class="flex items-center p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl border border-emerald-200">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fa fa-university text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-emerald-800">Relator Interno</p>
                                    <p class="text-sm text-emerald-600">Pertenece a la Universidad</p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center p-4 bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl border border-amber-200">
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mr-4 shadow-lg">
                                    <i class="fa fa-external-link-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-amber-800">Relator Externo</p>
                                    <p class="text-sm text-amber-600">Colaborador externo</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna Cursos y Actividades --}}
            <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                {{-- Cursos Dictados --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
                        <h2 class="text-white font-semibold flex items-center">
                            <i class="fa fa-graduation-cap mr-3"></i>
                            Cursos y Talleres Dictados
                            <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-sm">{{ $totalCursos }}</span>
                        </h2>
                    </div>
                    
                    @if($cursos->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($cursos as $curso)
                                <div class="p-4 sm:p-5 hover:bg-blue-50/50 transition-colors">
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.courses.show', $curso->cur_id) }}" 
                                               class="font-semibold text-slate-800 hover:text-blue-600 transition-colors line-clamp-2">
                                                {{ $curso->cur_nombre }}
                                            </a>
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                @if($curso->categoria)
                                                    <span class="inline-flex items-center px-2 py-1 bg-amber-100 text-amber-700 text-xs font-medium rounded-lg">
                                                        {{ $curso->categoria->nom_categoria }}
                                                    </span>
                                                @endif
                                                @if($curso->evento)
                                                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded-lg">
                                                        {{ Str::limit($curso->evento->eve_nombre, 20) }}
                                                    </span>
                                                @endif
                                                <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-600 text-xs font-medium rounded-lg">
                                                    {{ $curso->cur_horas ?? 0 }}h
                                                </span>
                                                @if($curso->cur_estado == 1)
                                                    <span class="inline-flex items-center px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-lg">
                                                        Activo
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-1 bg-slate-200 text-slate-600 text-xs font-medium rounded-lg">
                                                        Terminado
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.courses.show', $curso->cur_id) }}" 
                                           class="flex-shrink-0 inline-flex items-center px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-700 text-white text-sm font-medium rounded-lg hover:from-blue-600 hover:to-blue-800 transition-all shadow-md">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Ver
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Paginación de Cursos --}}
                        @if($cursos->hasPages())
                            <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white">
                                {{ $cursos->appends(request()->except('cursos_page'))->links() }}
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                            </div>
                            <p class="text-slate-500">Este relator aún no ha dictado cursos</p>
                        </div>
                    @endif
                </div>

                {{-- Historial de Sesiones --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-4">
                        <h2 class="text-white font-semibold flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Historial de Sesiones
                            <span class="ml-2 px-2 py-0.5 bg-white/20 rounded-full text-sm">{{ $totalProgramas }}</span>
                        </h2>
                    </div>
                    
                    @if($programas->count() > 0)
                        <div class="divide-y divide-slate-100">
                            @foreach($programas as $programa)
                                <div class="flex items-center gap-4 p-4 hover:bg-amber-50/50 transition-colors">
                                    <div class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white font-bold shadow">
                                        {{ ($programas->currentPage() - 1) * $programas->perPage() + $loop->iteration }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-slate-800 truncate">
                                            {{ $programa->curso->cur_nombre ?? 'Curso no encontrado' }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500">
                                            @if($programa->pro_inicia)
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    {{ \Carbon\Carbon::parse($programa->pro_inicia)->format('d/m/Y') }}
                                                </span>
                                            @endif
                                            @if($programa->pro_hora_inicio)
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $programa->pro_hora_inicio }}
                                                </span>
                                            @endif
                                            @if($programa->pro_lugar)
                                                <span class="flex items-center">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                    {{ Str::limit($programa->pro_lugar, 15) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        {{-- Paginación de Programas --}}
                        @if($programas->hasPages())
                            <div class="border-t border-slate-100 bg-gradient-to-b from-slate-50 to-white">
                                {{ $programas->appends(request()->except('programas_page'))->links() }}
                            </div>
                        @endif
                    @else
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-slate-500">No hay sesiones registradas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

