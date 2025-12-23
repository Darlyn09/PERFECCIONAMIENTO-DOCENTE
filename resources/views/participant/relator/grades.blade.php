@extends('layouts.participant')

@section('content')
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <a href="{{ route('participant.relator.course_students', $programa->cur_id) }}"
                    class="inline-flex items-center text-slate-500 hover:text-blue-600 font-medium mb-2 transition-colors">
                    <i class="fa fa-arrow-left mr-2"></i> Volver a Lista de Alumnos
                </a>
                <h1 class="text-2xl font-bold text-slate-800">Calificaciones y Asistencia</h1>
                <p class="text-slate-500">Sesión: {{ $programa->pro_id }} ({{ $programa->pro_horario ?? 'Sin horario' }})
                </p>
                <p class="text-slate-500 text-sm">{{ $programa->curso->cur_nombre }}</p>
            </div>
            
            {{-- FUTURE: Add Excel Export/Import here if requested for Relator --}}
        </div>

        <div class="grid grid-cols-1 gap-6">
            {{-- Tabla de Calificaciones --}}
            <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden flex flex-col h-[600px]">
                <form action="{{ route('participant.relator.update_grades', $programa->pro_id) }}" method="POST" class="flex flex-col h-full">
                    @csrf
                    <div class="px-6 py-4 border-b border-slate-100 bg-gradient-to-r from-emerald-50 to-white flex justify-between items-center flex-shrink-0">
                        <h2 class="font-bold text-slate-800 flex items-center">
                            <span class="bg-emerald-100 text-emerald-600 p-2 rounded-lg mr-3">
                                <i class="fa fa-users"></i>
                            </span>
                            Listado de Estudiantes
                        </h2>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all hover:scale-[1.02] active:scale-95">
                            <i class="fa fa-save mr-2"></i> Guardar Notas
                        </button>
                    </div>

                    <div class="overflow-auto flex-grow relative">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr class="text-slate-500 text-xs uppercase tracking-wider">
                                    <th class="px-6 py-4 font-bold border-b border-slate-200 bg-slate-50">Estudiante</th>
                                    <th class="px-6 py-4 font-bold border-b border-slate-200 bg-slate-50 w-32 text-center">Nota (1.0 - 7.0)</th>
                                    <th class="px-6 py-4 font-bold border-b border-slate-200 bg-slate-50 w-32 text-center">% Asistencia
                                    </th>
                                    <th class="px-6 py-4 font-bold border-b border-slate-200 bg-slate-50 w-32 text-center">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($programa->inscripciones as $ins)
                                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 mr-4">
                                                   <div class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-100 to-teal-100 text-emerald-700 flex items-center justify-center font-bold text-sm border-2 border-white shadow-sm">
                                                        {{ strtoupper(substr($ins->participante->par_nombre, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-800 group-hover:text-emerald-700 transition-colors">
                                                        {{ $ins->participante->par_nombre }} {{ $ins->participante->par_apellidos }}
                                                    </div>
                                                    <div class="text-xs text-slate-400">{{ $ins->participante->par_correo }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" step="0.1" min="1" max="7"
                                                name="notas[{{ $ins->ins_id }}]" value="{{ $ins->informacion->inf_nota ?? '' }}"
                                                class="w-full text-center border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-bold text-slate-700 py-2 shadow-sm"
                                                placeholder="-">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" step="1" min="0" max="100"
                                                name="asistencias[{{ $ins->ins_id }}]" value="{{ $ins->informacion->inf_asistencia ?? '' }}"
                                                class="w-full text-center border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-700 py-2 shadow-sm"
                                                placeholder="%">
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php $nota = $ins->informacion->inf_nota ?? 0; @endphp
                                            @if ($nota >= 4.0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200 shadow-sm">
                                                    <i class="fa fa-check mr-1.5"></i> Aprobado</span>
                                            @elseif($nota > 0)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full border border-red-200 shadow-sm">
                                                    <i class="fa fa-times mr-1.5"></i> Reprobado</span>
                                            @else
                                                <span class="text-slate-400 text-xs italic">Pendiente</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                           <div class="flex flex-col items-center">
                                                <i class="fa fa-users-slash text-4xl mb-3 opacity-50"></i>
                                                <p>No hay alumnos inscritos en esta sesión.</p>
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
    </div>
@endsection
