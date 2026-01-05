@extends('layouts.admin')

@section('title', 'Perfil de Usuario')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 -m-4 p-6">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex items-center px-4 md:px-5 py-2 md:py-2.5 mb-4 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-sm md:text-base font-semibold rounded-xl shadow-lg hover:from-slate-800 hover:to-slate-900 hover:shadow-xl transition-all group">
            <i class="fa fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
            <span class="hidden md:inline">Volver a Usuarios</span>
            <span class="md:hidden">Volver</span>
        </a>

        @if(session('success'))
            <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Header Dark Hero --}}
        <div class="relative mb-6 bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 rounded-2xl overflow-hidden shadow-2xl p-8">
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 opacity-20">
                <div class="absolute -right-20 -top-20 w-80 h-80 border-[20px] border-amber-400/30 rounded-full"></div>
                <div class="absolute -left-16 -bottom-16 w-64 h-64 border-[16px] border-blue-400/20 rounded-full"></div>
            </div>

            <div class="relative flex flex-col md:flex-row md:items-center gap-6">
                {{-- Avatar --}}
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center shadow-xl border-4 border-amber-400/30">
                        <span class="text-4xl sm:text-5xl font-bold text-white">
                            {{ strtoupper(substr($usuario->par_nombre ?? 'U', 0, 1)) }}{{ strtoupper(substr($usuario->par_apellido ?? '', 0, 1)) }}
                        </span>
                    </div>
                </div>

                {{-- Info Principal --}}
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="inline-flex items-center px-3 py-1 bg-emerald-500 text-white text-xs font-bold rounded-full shadow-lg">
                            <span class="w-2 h-2 bg-white rounded-full mr-1.5 animate-pulse"></span>
                            Cuenta Activa
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-indigo-500 text-white text-xs font-bold rounded-full shadow-lg">
                            <i class="fa fa-briefcase mr-1.5"></i> {{ $usuario->par_cargo ?? 'Sin Cargo' }}
                        </span>
                    </div>
                    
                    <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">
                        {{ $usuario->par_nombre }} {{ $usuario->par_apellido }}
                    </h1>
                    
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-6 text-blue-100 text-sm">
                        <span class="flex items-center">
                            <i class="fa fa-envelope mr-2"></i> {{ $usuario->par_correo }}
                        </span>
                        <span class="flex items-center">
                            <i class="fa fa-id-card mr-2"></i> {{ $usuario->par_login }}
                        </span>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="flex flex-col gap-2">
                    <a href="{{ route('admin.users.edit', $usuario->par_login) }}" 
                       class="inline-flex items-center justify-center px-5 py-2.5 bg-amber-500 text-white rounded-xl hover:bg-amber-600 transition-all shadow-lg font-bold">
                        <i class="fa fa-pencil-alt mr-2"></i> Editar Usuario
                    </a>
                    <form id="resend-form" action="{{ route('admin.users.resend', $usuario->par_login) }}" method="POST">
                        @csrf
                        <button type="button" @click.prevent="$dispatch('confirm-action', { 
                            title: 'Reenviar Credenciales', 
                            message: 'Esto generará una nueva contraseña y la enviará por correo a {{ $usuario->par_correo }}. ¿Continuar?', 
                            type: 'warning',
                            itemName: '{{ $usuario->par_nombre }} {{ $usuario->par_apellido }}',
                            formId: 'resend-form' 
                        })"
                           class="w-full inline-flex items-center justify-center px-5 py-2.5 bg-white/10 backdrop-blur-md text-white border border-white/20 rounded-xl hover:bg-white/20 transition-all shadow-lg font-medium text-sm">
                            <i class="fa fa-key mr-2"></i> Reenviar Credenciales
                        </button>
                    </form>
                </div>
            </div>

            {{-- Stats Rápidas en Header --}}
            <div class="grid grid-cols-3 gap-3 sm:gap-4 mt-8 pt-6 border-t border-white/10">
                <div class="text-center">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $totalCursos }}</p>
                    <p class="text-xs sm:text-sm text-blue-200 uppercase tracking-wide">Cursos Realizados</p>
                </div>
                <div class="text-center border-l border-white/10">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $cursosAprobados }}</p>
                    <p class="text-xs sm:text-sm text-blue-200 uppercase tracking-wide">Aprobados</p>
                </div>
                <div class="text-center border-l border-white/10">
                    <p class="text-2xl sm:text-3xl font-bold text-white">{{ $cursosAprobados }}</p> {{-- Asumiendo que aprobados = certificados --}}
                    <p class="text-xs sm:text-sm text-blue-200 uppercase tracking-wide">Certificados</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Columna Izquierda --}}
            <div class="space-y-6">
                {{-- Información Detallada --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 flex items-center">
                            <i class="fa fa-info-circle text-blue-500 mr-2"></i> Detalles de la Cuenta
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Perfil de Sistema</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $usuario->par_perfil == 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-100 text-slate-800' }}">
                                <i class="fa {{ $usuario->par_perfil == 'admin' ? 'fa-user-shield' : 'fa-user' }} mr-1.5"></i>
                                {{ ucfirst($usuario->par_perfil) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Facultad / Unidad</p>
                            <p class="text-slate-800 font-medium flex items-center">
                                <i class="fa fa-university text-slate-400 mr-2"></i>
                                {{ $usuario->par_facultad ?? 'No registrada' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Departamento</p>
                            <p class="text-slate-800 font-medium flex items-center">
                                <i class="fa fa-building text-slate-400 mr-2"></i>
                                {{ $usuario->par_departamento ?? 'No registrado' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Sede</p>
                            <p class="text-slate-800 font-medium flex items-center">
                                <i class="fa fa-map-marker-alt text-slate-400 mr-2"></i>
                                {{ $usuario->par_sede ?? 'No registrada' }}
                            </p>
                        </div>
                        @if($usuario->fecha_registro)
                            <div>
                                <p class="text-xs text-slate-500 uppercase tracking-wide font-bold mb-1">Fecha de Registro</p>
                                <p class="text-slate-800 font-medium flex items-center">
                                    <i class="fa fa-calendar text-slate-400 mr-2"></i>
                                    {{ \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Áreas de Desarrollo --}}
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-slate-50 px-6 py-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800 flex items-center">
                            <i class="fa fa-chart-pie text-indigo-500 mr-2"></i> Áreas de Desarrollo
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($areas->count() > 0)
                            <div class="space-y-4">
                                @foreach($areas as $area => $count)
                                    @php 
                                        $porcentaje = $totalCursos > 0 ? ($count / $totalCursos) * 100 : 0;
                                    @endphp
                                    <div>
                                        <div class="flex justify-between items-end mb-1">
                                            <span class="text-sm font-medium text-slate-700 truncate block max-w-[70%]">{{ $area ?? 'General' }}</span>
                                            <span class="text-xs text-slate-500">{{ $count }} cursos</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-2">
                                            <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: {{ $porcentaje }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-slate-400">
                                <i class="fa fa-chart-bar text-3xl mb-2 opacity-50"></i>
                                <p class="text-sm">Sin datos suficientes</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Columna Derecha (Historial) --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 flex justify-between items-center">
                        <h3 class="font-bold text-white flex items-center">
                            <i class="fa fa-history mr-2"></i> Historial Académico
                        </h3>
                        <span class="text-xs bg-white/20 text-white px-2 py-1 rounded-lg">
                            {{ $inscripciones->total() }} registros
                        </span>
                    </div>

                    @if($inscripciones->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead class="bg-slate-50 text-slate-500 uppercase tracking-wider font-semibold text-xs border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-4">Curso / Taller</th>
                                        <th class="px-6 py-4">Fecha Inicio</th>
                                        <th class="px-6 py-4 text-center">Estado</th>
                                        <th class="px-6 py-4 text-center">Certificado</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($inscripciones as $ins)
                                        @php
                                            $fechaTermino = $ins->cur_fecha_termino ?? $ins->eve_finaliza;
                                            $finalizado = $fechaTermino && \Carbon\Carbon::parse($fechaTermino)->isPast();
                                            
                                            $nota = str_replace(',', '.', $ins->inf_nota); // Usar inf_nota
                                            $tieneNota = is_numeric($nota);
                                            // Aprobado usando lógica centralizada
                                            $aprobado = $ins->isApproved();
                                        @endphp
                                        <tr class="hover:bg-blue-50/30 transition-colors group">
                                            <td class="px-6 py-4">
                                                <div class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $ins->cur_nombre }}</div>
                                                <div class="text-xs text-slate-500 mt-0.5">
                                                    <span class="inline-block w-2 h-2 rounded-full {{ $ins->nom_categoria ? 'bg-amber-400' : 'bg-slate-300' }} mr-1"></span>
                                                    {{ $ins->nom_categoria ?? 'Sin categoría' }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600 whitespace-nowrap">
                                                <i class="fa fa-calendar-alt text-slate-400 mr-1.5"></i>
                                                {{ $ins->cur_fecha_inicio ? \Carbon\Carbon::parse($ins->cur_fecha_inicio)->format('d/m/Y') : '-' }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($aprobado)
                                                    <span class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold" title="Nota: {{ $ins->inf_nota }}">
                                                        <i class="fa fa-check mr-1.5"></i> Aprobado
                                                    </span>
                                                @elseif($finalizado && $tieneNota)
                                                    <span class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold" title="Nota: {{ $ins->inf_nota }}">
                                                        <i class="fa fa-times mr-1.5"></i> Reprobado
                                                    </span>
                                                @elseif($finalizado)
                                                    <span class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                                                        <i class="fa fa-clock mr-1.5"></i> Finalizado
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold">
                                                        <i class="fa fa-spinner fa-spin mr-1.5"></i> En Curso
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($aprobado)
                                                    <a href="{{ route('admin.certificates.download', ['login' => $usuario->par_login, 'course' => $ins->cur_id]) }}" 
                                                       class="inline-flex items-center justify-center w-8 h-8 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all shadow-sm transform hover:scale-110" 
                                                       title="Descargar Certificado"
                                                       target="_blank">
                                                        <i class="fa fa-file-download"></i>
                                                    </a>
                                                @else
                                                    <span class="text-slate-300 cursor-not-allowed" title="Requiere aprobación">
                                                        <i class="fa fa-lock"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-100 bg-slate-50">
                            {{ $inscripciones->links() }}
                        </div>
                    @else
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fa fa-folder-open text-3xl text-slate-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-slate-800">Sin inscripciones</h3>
                            <p class="text-slate-500">El usuario no ha participado en cursos aún.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
