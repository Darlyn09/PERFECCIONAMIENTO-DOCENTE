@extends('layouts.admin')

@section('title', 'Perfil del Relator')

@section('content')
    <div class="min-h-screen -m-4 p-4 sm:p-6 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">

        <div class="max-w-6xl mx-auto space-y-6">
            {{-- Breadcrumb --}}
            <div>
                <a href="{{ route('admin.relators.index') }}"
                    class="text-sm text-slate-500 hover:text-blue-600 mb-2 inline-flex items-center transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al listado
                </a>
            </div>

            {{-- Encabezado de Perfil --}}
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100 relative">
                <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                <div class="px-6 pb-6">
                    <div class="relative flex justify-between items-end -mt-12 mb-4">
                        <div class="flex items-end">
                            <div class="w-24 h-24 rounded-2xl bg-white p-1 shadow-lg">
                                <div
                                    class="w-full h-full rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center text-blue-600 font-bold text-3xl border border-blue-200">
                                    {{ strtoupper(substr($relator->rel_nombre, 0, 1)) }}{{ strtoupper(substr($relator->rel_apellido, 0, 1)) }}
                                </div>
                            </div>
                            <div class="ml-4 mb-1">
                                <h1 class="text-2xl font-bold text-slate-800">{{ $relator->rel_nombre }}
                                    {{ $relator->rel_apellido }}</h1>
                                <p class="text-slate-500 text-sm flex items-center">
                                    <i class="fa fa-id-card mr-1.5 text-blue-400"></i> {{ $relator->rel_login }}
                                    <span class="mx-2 text-slate-300">|</span>
                                    <span
                                        class="text-blue-600 font-medium">{{ $relator->rel_cargo ?? 'Sin cargo definido' }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.relators.edit', $relator->rel_login) }}"
                                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-medium rounded-xl hover:bg-slate-50 transition-colors shadow-sm">
                                <i class="fa fa-pen mr-2 text-amber-500"></i> Editar
                            </a>
                            <button type="button" @click="$dispatch('confirm-action', { 
                                            title: 'Eliminar Relator', 
                                            message: '¿Estás seguro de eliminar el perfil de {{ $relator->rel_nombre }}? Esta acción es irreversible.',
                                            itemName: '{{ $relator->rel_login }}',
                                            type: 'delete',
                                            formId: 'delete-relator-form'
                                        })"
                                class="px-4 py-2 bg-white border border-red-100 text-red-600 font-medium rounded-xl hover:bg-red-50 transition-colors shadow-sm">
                                <i class="fa fa-trash mr-2"></i> Eliminar
                            </button>
                            <form id="delete-relator-form"
                                action="{{ route('admin.relators.destroy', $relator->rel_login) }}" method="POST"
                                class="hidden">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4 border-t border-slate-100">
                        <div class="flex items-center text-sm text-slate-600">
                            <i class="fa fa-envelope w-5 text-slate-400"></i>
                            {{ $relator->rel_correo }}
                        </div>
                        <div class="flex items-center text-sm text-slate-600">
                            <i class="fa fa-phone w-5 text-slate-400"></i>
                            {{ $relator->rel_fono ?? 'No registrado' }}
                        </div>
                        <div class="flex items-center text-sm text-slate-600">
                            <i class="fa fa-building w-5 text-slate-400"></i>
                            {{ $relator->rel_facultad ?? 'Facultad no especificada' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grid de KPIs --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Cursos -->
                <div class="bg-white rounded-xl p-5 shadow-lg border border-slate-100 flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mr-4">
                        <i class="fa fa-chalkboard-teacher text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Cursos Dictados</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalCursos }}</p>
                    </div>
                </div>

                <!-- Total Alumnos -->
                <div class="bg-white rounded-xl p-5 shadow-lg border border-slate-100 flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-indigo-100 text-indigo-600 flex items-center justify-center mr-4">
                        <i class="fa fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Total Alumnos</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $totalAlumnos }}</p>
                    </div>
                </div>

                <!-- Aprobación -->
                <div class="bg-white rounded-xl p-5 shadow-lg border border-slate-100 flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mr-4">
                        <i class="fa fa-check-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Aprobación Prom.</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $promedioAprobacion }}%</p>
                    </div>
                </div>

                <!-- Certificados -->
                <div class="bg-white rounded-xl p-5 shadow-lg border border-slate-100 flex items-center">
                    <div class="w-12 h-12 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mr-4">
                        <i class="fa fa-certificate text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase font-semibold tracking-wider">Certificados</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $certificadosEmitidos }}</p>
                    </div>
                </div>
            </div>

            {{-- Historial de Actividad --}}
            <div class="bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Historial de Cursos</h3>
                    <span class="text-xs font-medium text-slate-500 bg-white px-2 py-1 rounded border border-slate-200">
                        Últimos registros
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Curso / Programa</th>
                                <th class="px-6 py-3 font-semibold">Fecha</th>
                                <th class="px-6 py-3 font-semibold text-center">Inscritos</th>
                                <th class="px-6 py-3 font-semibold text-center">Estado</th>
                                <th class="px-6 py-3 font-semibold text-right">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($historialCursos as $curso)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-slate-700">
                                            {{ $curso->curso->cur_nombre ?? 'Curso sin nombre' }}</div>
                                        <div class="text-xs text-slate-400">ID: {{ $curso->pro_id ?? '---' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{-- Fecha simulada o real si existe --}}
                                        {{ isset($curso->fecha_inicio) ? \Carbon\Carbon::parse($curso->fecha_inicio)->format('d/m/Y') : 'Fecha no disp.' }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm font-medium text-slate-700">
                                        {{ $curso->inscripciones_count ?? 0 }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            Finalizado
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="#"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium uppercase tracking-wide">
                                            Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fa fa-folder-open text-slate-400"></i>
                                            </div>
                                            <p>No hay historial de cursos para este relator.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection