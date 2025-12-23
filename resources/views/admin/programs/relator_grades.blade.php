@extends('layouts.admin')

@section('title', 'Calificar Relatores')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.courses.show', $programa->cur_id) }}"
                    class="inline-flex items-center text-slate-500 hover:text-blue-600 font-medium mb-2 transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver al Curso
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Evaluación de Relatores</h1>
                <p class="text-slate-500">
                    Programa: <span class="font-semibold">{{ $programa->curso->cur_nombre }}</span>
                    <span class="text-slate-300 mx-2">|</span>
                    Sesión: {{ $programa->pro_id }}
                </p>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded relative">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
        </div>

        {{-- Contenido Principal --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
            <form action="{{ route('admin.programs.update_relator_grades', $programa->pro_id) }}" method="POST">
                @csrf

                {{-- Toolbar --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                    <h2 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="fa fa-chalkboard-teacher text-blue-500"></i>
                        Nómina de Relatores Asignados
                    </h2>
                    <div class="flex gap-2">
                        <button type="submit" name="action" value="save"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-md transition-all">
                            <i class="fa fa-save mr-2"></i> Guardar Calificaciones
                        </button>
                        <button type="submit" name="action" value="certify"
                            class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg shadow-md transition-all"
                            onclick="return confirm('¿Está seguro de certificar a los relatores aprobados? Esto generará sus registros de certificación.')">
                            <i class="fa fa-certificate mr-2"></i> Certificar Aprobados
                        </button>
                    </div>
                </div>

                {{-- Tabla --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 border-b">Relator / Docente</th>
                                <th class="px-6 py-4 border-b text-center w-32">Nota (1.0-7.0)</th>
                                <th class="px-6 py-4 border-b text-center w-32">Asistencia %</th>
                                <th class="px-6 py-4 border-b text-center w-40">Estado</th>
                                <th class="px-6 py-4 border-b text-center w-40">Certificado</th>
                                <th class="px-6 py-4 border-b">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($programa->relatores as $relator)
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold mr-3 border border-blue-200">
                                                {{ substr($relator->rel_nombre, 0, 1) }}{{ substr($relator->rel_apellido ?? '', 0, 1) }}
                                            </div>
                                            <div>
                                                <div
                                                    class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                                    {{ $relator->rel_nombre }} {{ $relator->rel_apellido }}
                                                </div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $relator->rel_rut ?? $relator->rel_login }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Nota --}}
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.1" min="1" max="7" name="notas[{{ $relator->rel_login }}]"
                                            value="{{ $relator->pivot->rr_nota }}"
                                            class="w-full text-center font-bold text-slate-700 border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                            placeholder="-">
                                    </td>

                                    {{-- Asistencia --}}
                                    <td class="px-6 py-4">
                                        <input type="number" step="1" min="0" max="100"
                                            name="asistencias[{{ $relator->rel_login }}]"
                                            value="{{ $relator->pivot->rr_asistencia }}"
                                            class="w-full text-center text-slate-700 border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                                            placeholder="%">
                                    </td>

                                    {{-- Estado (Visual) --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($relator->pivot->rr_nota >= 4.0)
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                <i class="fa fa-check mr-1"></i> Aprobado
                                            </span>
                                        @elseif(!is_null($relator->pivot->rr_nota))
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                <i class="fa fa-times mr-1"></i> Reprobado
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Pendiente</span>
                                        @endif
                                    </td>

                                    {{-- Certificado --}}
                                    <td class="px-6 py-4 text-center">
                                        @if($relator->pivot->rr_certificado)
                                            <a href="{{ route('admin.relators.certificate', ['programId' => $programa->pro_id, 'relLogin' => $relator->rel_login]) }}"
                                                target="_blank"
                                                class="text-blue-600 hover:text-blue-800 hover:underline text-xs font-bold flex items-center justify-center gap-1">
                                                <i class="fa fa-download"></i> Descargar
                                            </a>
                                            <span class="text-[10px] text-slate-400 block mt-1">
                                                {{ \Carbon\Carbon::parse($relator->pivot->rr_certificado)->format('d/m/Y') }}
                                            </span>
                                        @else
                                            <span class="text-slate-300 text-xs">-</span>
                                        @endif
                                    </td>

                                    {{-- Observaciones --}}
                                    <td class="px-6 py-4">
                                        <input type="text" name="observaciones[{{ $relator->rel_login }}]"
                                            value="{{ $relator->pivot->rr_observaciones }}"
                                            class="w-full text-xs text-slate-600 border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                            placeholder="Comentarios...">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <i class="fa fa-user-slash text-4xl mb-3 opacity-30"></i>
                                            <p>No hay relatores asignados a este programa.</p>
                                            <a href="{{ route('admin.programs.teachers', $programa->pro_id) }}"
                                                class="mt-2 text-blue-600 hover:underline text-sm font-bold">
                                                Gestionar Docentes
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection